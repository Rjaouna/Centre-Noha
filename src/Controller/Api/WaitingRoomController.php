<?php

namespace App\Controller\Api;

use App\Entity\WaitingRoom;
use App\Entity\PatientPrestation;
use App\Repository\RendezVousRepository;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FicheClientRepository;
use App\Repository\WaitingRoomRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/waiting-room', name: 'api_waiting_room_')]
class WaitingRoomController extends AbstractController
{
	private const STATUSES = ['EN_ATTENTE', 'APPELE', 'EN_CONSULTATION', 'TERMINE', 'ABSENT'];

	private const TRANSITIONS = [
		'EN_ATTENTE'      => ['APPELE', 'ABSENT'],
		'APPELE'          => ['EN_CONSULTATION', 'EN_ATTENTE'],
		'EN_CONSULTATION' => ['TERMINE'],
		'TERMINE'         => ['EN_ATTENTE'],
		'ABSENT'          => ['EN_ATTENTE'],
	];

	private function normalizeStatus(?string $s): string
	{
		$v = strtoupper(trim((string) $s));
		if ($v === '' || !in_array($v, self::STATUSES, true)) {
			return 'EN_ATTENTE';
		}
		return $v;
	}

	private function dayRange(): array
	{
		$start = new \DateTimeImmutable('today');
		$end = $start->modify('+1 day');
		return [$start, $end];
	}

	/**
	 * ✅ GET /api/waiting-room/list
	 * Renvoie la salle du jour (isActive=true) groupée par statut
	 */
	#[Route('/list', name: 'list', methods: ['GET'])]
	public function list(
		WaitingRoomRepository $wrRepo,
		EntityManagerInterface $em,
	): JsonResponse {
		try {
			$praticien = $this->getUser();
			if (!$praticien) {
				return $this->json(['success' => false, 'message' => 'Non authentifié'], 401);
			}



			[$start, $end] = $this->dayRange();
			$now = new \DateTimeImmutable();

			$rows = $wrRepo->createQueryBuilder('w')
				->leftJoin('w.patient', 'p')->addSelect('p')
				->leftJoin('w.rdv', 'r')->addSelect('r')
				->andWhere('w.praticien = :praticien')
				->andWhere('w.queueDate >= :start AND w.queueDate < :end')
				->andWhere('w.isActive = true')
				->setParameter('praticien', $praticien)
				->setParameter('start', $start)
				->setParameter('end', $end)
				->getQuery()
				->getResult();

			$grouped = [
				'EN_ATTENTE' => [],
				'APPELE' => [],
				'EN_CONSULTATION' => [],
				'TERMINE' => [],
				'ABSENT' => [],
			];

			foreach ($rows as $w) {
				/** @var WaitingRoom $w */
				$p = $w->getPatient();
				if (!$p) continue;

				$statut = $this->normalizeStatus($w->getStatut());

				$rdv = $w->getRdv();
				$rdvStart = null;
				$rdvPassed = false;

				if ($rdv && $rdv->getDate() && $rdv->getHeureDebut()) {
					$rdvStartDT = new \DateTimeImmutable(
						$rdv->getDate()->format('Y-m-d') . ' ' . $rdv->getHeureDebut()->format('H:i:s')
					);
					$rdvStart = $rdvStartDT->format('Y-m-d H:i:s');
					$rdvPassed = $rdvStartDT < $now;
				}
				$prestations = $w->getPrestation()->map(static function (PatientPrestation $pp) {
					$p = $pp->getPrestation(); // PrestationPrice (catalogue)

					return [
						'id' => $pp->getId(),
						'prestationId' => $p?->getId(),
						'nom' => $pp->getNom() ?? $p?->getNom(),
						'categorie' => $p?->getCategorie(),
						'quantite' => $pp->getQuantite(),
						'prixUnitaire' => $pp->getPrixUnitaire(),      // string decimal si tu as changé
						'total' => $pp->getTotalPrestation(),          // string decimal si tu as changé
						'createdAt' => $pp->getCreatedAt()?->format('Y-m-d H:i:s'),
					];
				})->toArray();

				$arriveAt = $w->getArriveAt();
				$grouped[$statut][] = [
					// ✅ id = WaitingRoom.id (IMPORTANT pour PATCH)
					'id' => $w->getId(),

					'patientId' => $p->getId(),
					'nom' => $p->getNom(),
					'prenom' => $p->getPrenom(),
					'ville' => $p->getVille(),
					'telephone' => $p->getTelephone(),
					'typeMaladie' => $p->getTypeMaladie(),

					'statut' => $statut,
					'note' => $w->getNote(),
					// ✅ prestations sérialisées
					'prestations' => $prestations,

					'arriveAt' => $arriveAt ? $arriveAt->format('Y-m-d H:i:s') : null,

					'hasRdv' => (bool) $w->hasRdv(),
					'rdvId' => $rdv?->getId(),

					'rdvStart' => $rdvStart,
					'rdvPassed' => $rdvPassed,
				];
			}

			// Tri : arriveAt d’abord, sinon rdvStart
			foreach ($grouped as &$list) {
				usort($list, function ($a, $b) {
					$aa = $a['arriveAt'] ?? null;
					$bb = $b['arriveAt'] ?? null;
					if ($aa && $bb) return strcmp($aa, $bb);
					if ($aa && !$bb) return -1;
					if (!$aa && $bb) return 1;

					$ra = $a['rdvStart'] ?? null;
					$rb = $b['rdvStart'] ?? null;
					if ($ra && $rb) return strcmp($ra, $rb);
					if ($ra && !$rb) return -1;
					if (!$ra && $rb) return 1;

					return ($a['id'] <=> $b['id']);
				});
			}
			unset($list);

			return $this->json(['success' => true, 'data' => $grouped]);
		} catch (\Throwable $e) {
			return $this->json([
				'success' => false,
				'message' => 'Erreur serveur',
				'debug' => $e->getMessage(),
			], 500);
		}
	}

	/**
	 * ✅ POST /api/waiting-room/arrive
	 * Body: { "patientId": 123 }
	 * -> crée/réactive la ligne WaitingRoom du jour en EN_ATTENTE
	 */
	#[Route('/arrive', name: 'arrive', methods: ['POST'])]
	public function arrive(
		Request $request,
		EntityManagerInterface $em,
		FicheClientRepository $patientRepo,
		WaitingRoomRepository $wrRepo,
		RendezVousRepository $rdvRepo,
	): JsonResponse {
		try {
			$praticien = $this->getUser();
			if (!$praticien) {
				return $this->json(['success' => false, 'message' => 'Non authentifié'], 401);
			}

			$payload = json_decode($request->getContent() ?: '{}', true);
			$patientId = isset($payload['patientId']) ? (int) $payload['patientId'] : 0;
			if ($patientId <= 0) {
				return $this->json(['success' => false, 'message' => 'patientId manquant'], 400);
			}
			$note = isset($payload['note']) ? (string) $payload['note'] : '';

			$patient = $patientRepo->find($patientId);
			if (!$patient) {
				return $this->json(['success' => false, 'message' => 'Patient introuvable'], 404);
			}

			[$start, $end] = $this->dayRange();
			$now = new \DateTimeImmutable();

			// ✅ si déjà une ligne du jour (active ou pas) pour ce patient/praticien -> on la reprend
			$existing = $wrRepo->createQueryBuilder('w')
				->andWhere('w.praticien = :praticien')
				->andWhere('w.patient = :patient')
				->andWhere('w.queueDate >= :start AND w.queueDate < :end')
				->setParameter('praticien', $praticien)
				->setParameter('patient', $patient)
				->setParameter('start', $start)
				->setParameter('end', $end)
				->setMaxResults(1)
				->getQuery()
				->getOneOrNullResult();

			$w = $existing ?: new WaitingRoom();
			if (!$existing) {
				$w->setPatient($patient);
				$w->setPraticien($praticien);
				$w->setQueueDate($now);
				$w->setCreatedAt($now);
				$w->setNote($note);
			}

			// ✅ RDV du jour (si tu veux lier)
			$rdv = $rdvRepo->createQueryBuilder('r')
				->andWhere('r.praticien = :praticien')
				->andWhere('r.patient = :patient')
				->andWhere('r.date >= :start AND r.date < :end')
				->andWhere('r.statut != :annule')
				->setParameter('praticien', $praticien)
				->setParameter('patient', $patient)
				->setParameter('start', $start)
				->setParameter('end', $end)
				->setParameter('annule', 'annule')
				->orderBy('r.heureDebut', 'ASC')
				->setMaxResults(1)
				->getQuery()
				->getOneOrNullResult();

			$w->setRdv($rdv);
			$w->setHasRdv((bool) $rdv);

			$w->setStatut('EN_ATTENTE');
			$w->setIsActive(true);

			// arriveAt obligatoire dans ton entity
			if ($w->getArriveAt() === null) {
				$w->setArriveAt($now);
			}

			$w->setUpdatedAt($now);

			$em->persist($w);
			$em->flush();

			return $this->json([
				'success' => true,
				'data' => [
					'waitingRoomId' => $w->getId(),
					'statut' => $w->getStatut(),
					'patientId' => $patient->getId(),
				],
			], $existing ? 200 : 201);
		} catch (\Throwable $e) {
			return $this->json([
				'success' => false,
				'message' => 'Erreur serveur',
				'debug' => $e->getMessage(),
			], 500);
		}
	}

	/**
	 * ✅ PATCH /api/waiting-room/{id}/status
	 * { "statut": "APPELE" }
	 * -> {id} = WaitingRoom.id
	 */
	#[Route('/{id}/status', name: 'status', methods: ['PATCH'])]
	public function patchStatus(
		int $id,
		Request $request,
		WaitingRoomRepository $wrRepo,
		EntityManagerInterface $em,
	): JsonResponse {
		try {
			$praticien = $this->getUser();
			if (!$praticien) {
				return $this->json(['success' => false, 'message' => 'Non authentifié'], 401);
			}

			$w = $wrRepo->find($id);
			if (!$w) {
				return $this->json(['success' => false, 'message' => 'Entrée salle introuvable'], 404);
			}

			if ($w->getPraticien()?->getId() !== $praticien->getId()) {
				return $this->json(['success' => false, 'message' => 'Accès refusé'], 403);
			}

			$payload = json_decode($request->getContent() ?: '{}', true);
			$to = isset($payload['statut']) ? strtoupper(trim((string) $payload['statut'])) : null;

			if (!$to || !in_array($to, self::STATUSES, true)) {
				return $this->json(['success' => false, 'message' => 'Statut invalide'], 400);
			}

			$from = $this->normalizeStatus($w->getStatut());

			if ($to === $from) {
				return $this->json([
					'success' => true,
					'data' => ['id' => $w->getId(), 'statut' => $from],
					'message' => 'Aucun changement',
				]);
			}

			$allowed = self::TRANSITIONS[$from] ?? [];
			if (!in_array($to, $allowed, true)) {
				return $this->json([
					'success' => false,
					'message' => "Transition non autorisée ($from → $to)",
				], 400);
			}

			$now = new \DateTimeImmutable();

			// timestamps de workflow
			if ($to === 'APPELE' && $w->getCalledAt() === null) {
				$w->setCalledAt($now);
			}
			if ($to === 'EN_CONSULTATION' && $w->getConsultationAt() === null) {
				$w->setConsultationAt($now);
			}
			if ($to === 'TERMINE' && $w->getDoneAt() === null) {
				$w->setDoneAt($now);
			}

			// arriveAt si jamais vide
			if ($w->getArriveAt() === null) {
				$w->setArriveAt($now);
			}

			$w->setStatut($to);
			$w->setIsActive(true); // on garde visible dans la salle
			$w->setUpdatedAt($now);

			$em->flush();

			return $this->json([
				'success' => true,
				'data' => ['id' => $w->getId(), 'statut' => $to],
			]);
		} catch (\Throwable $e) {
			return $this->json([
				'success' => false,
				'message' => 'Erreur serveur',
				'debug' => $e->getMessage(),
			], 500);
		}
	}
}
