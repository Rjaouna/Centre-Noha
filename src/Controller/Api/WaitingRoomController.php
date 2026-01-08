<?php

namespace App\Controller\Api;

use App\Repository\FicheClientRepository;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/waiting-room', name: 'api_waiting_room_')]
class WaitingRoomController extends AbstractController
{
	private const STATUSES = ['EN_ATTENTE', 'APPELE', 'EN_CONSULTATION', 'TERMINE', 'ABSENT'];

	private const TRANSITIONS = [
		'EN_ATTENTE'      => ['APPELE', 'ABSENT'],
		'APPELE'          => ['EN_CONSULTATION', 'EN_ATTENTE'],
		'EN_CONSULTATION' => ['TERMINE'],
		'TERMINE'         => ['EN_ATTENTE'], // ✅ autorisé
		'ABSENT'          => ['EN_ATTENTE'],
	];


	#[Route('/list', name: 'list', methods: ['GET'])]
	public function list(
		FicheClientRepository $ficheRepo,
		RendezVousRepository $rdvRepo,
	): JsonResponse {
		try {
			$today = new \DateTime('today');
			$tomorrow = (clone $today)->modify('+1 day');
			$now = new \DateTimeImmutable();

			// 1) RDV du jour
			$rdvs = $rdvRepo->createQueryBuilder('r')
				->andWhere('r.date >= :start')
				->andWhere('r.date < :end')
				->setParameter('start', $today)
				->setParameter('end', $tomorrow)
				->getQuery()
				->getResult();

			// Map RDV par patientId => rdvStart DateTimeImmutable (on garde le + tôt)
			$rdvByPatient = [];
			foreach ($rdvs as $r) {
				$p = $r->getPatient();
				if (!$p) continue;

				$pid = $p->getId();
				if (!$pid) continue;

				$date = $r->getDate();          // DateTime (date)
				$hdeb = $r->getHeureDebut();     // DateTime (time)

				// Compose "YYYY-mm-dd HH:ii:ss"
				$startStr = $date->format('Y-m-d') . ' ' . $hdeb->format('H:i:s');
				$start = new \DateTimeImmutable($startStr);

				if (!isset($rdvByPatient[$pid]) || $start < $rdvByPatient[$pid]) {
					$rdvByPatient[$pid] = $start;
				}
			}

			// 2) Patients isOpen = true (sans toucher au champ Statut en DQL)
			$openPatients = $ficheRepo->createQueryBuilder('p')
				->andWhere('p.isOpen = :open')
				->setParameter('open', true)
				->getQuery()
				->getResult();

			// 3) Fusion : isOpen + patients ayant RDV du jour
			$patientsById = [];
			foreach ($openPatients as $p) {
				$patientsById[$p->getId()] = $p;
			}

			// Ajoute les patients des RDV du jour (même s’ils ne sont pas isOpen)
			foreach ($rdvByPatient as $pid => $start) {
				if (!isset($patientsById[$pid])) {
					$p = $ficheRepo->find($pid);
					if ($p) $patientsById[$pid] = $p;
				}
			}

			// 4) Build rows + group
			$grouped = [
				'EN_ATTENTE' => [],
				'APPELE' => [],
				'EN_CONSULTATION' => [],
				'TERMINE' => [],
				'ABSENT' => [],
			];

			foreach ($patientsById as $p) {
				$pid = $p->getId();

				$statutRaw = $p->getStatut();
				$isOpen = (bool) $p->isOpen(); // null -> false
				$hasRdvToday = array_key_exists($pid, $rdvByPatient); // RDV du jour ?

				// ✅ Condition d’affichage :
				// - Doit être isOpen OU avoir un RDV aujourd’hui
				if (!$isOpen && !$hasRdvToday) {
					continue;
				}

				// ✅ Si tu veux VRAIMENT ne PAS afficher les statuts NULL/vides :
				if ($statutRaw === null || trim($statutRaw) === '') {
					continue;
				}

				$statut = strtoupper(trim($statutRaw));
				if (!in_array($statut, self::STATUSES, true)) {
					continue;
				}

				$rdvStart = $rdvByPatient[$pid] ?? null;

				$row = [
					'id' => $pid,
					'nom' => $p->getNom(),
					'prenom' => $p->getPrenom(),
					'ville' => $p->getVille(),
					'telephone' => $p->getTelephone(),
					'typeMaladie' => $p->getTypeMaladie(),
					'statut' => $statut,
					'rdvStart' => $rdvStart ? $rdvStart->format('Y-m-d H:i:s') : null,
					'rdvPassed' => $rdvStart ? ($rdvStart < $now) : false,
				];

				$grouped[$statut][] = $row;
			}

			// 5) tri : si rdvStart => par heure, sinon par id
			foreach ($grouped as $k => &$list) {
				usort($list, function ($a, $b) {
					$ta = $a['rdvStart'] ?? null;
					$tb = $b['rdvStart'] ?? null;

					if ($ta && $tb) return strcmp($ta, $tb);
					if ($ta && !$tb) return -1;
					if (!$ta && $tb) return 1;
					return ($b['id'] <=> $a['id']);
				});
			}
			unset($list);

			return $this->json([
				'success' => true,
				'data' => $grouped,
			]);
		} catch (\Throwable $e) {
			return $this->json([
				'success' => false,
				'message' => 'Erreur serveur',
				'debug' => $e->getMessage(),
			], 500);
		}
	}

	#[Route('/{id}/status', name: 'status', methods: ['PATCH'])]
	public function patchStatus(
		int $id,
		Request $request,
		FicheClientRepository $ficheRepo,
		EntityManagerInterface $em,
	): JsonResponse {
		try {
			$patient = $ficheRepo->find($id);
			if (!$patient) {
				return $this->json(['success' => false, 'message' => 'Patient introuvable'], 404);
			}

			$payload = json_decode($request->getContent() ?: '{}', true);
			$to = isset($payload['statut']) ? strtoupper(trim((string) $payload['statut'])) : null;

			if (!$to || !in_array($to, self::STATUSES, true)) {
				return $this->json(['success' => false, 'message' => 'Statut invalide'], 400);
			}

			$rawFrom = $patient->getStatut();               // peut être null
			$from = $rawFrom ? strtoupper(trim($rawFrom)) : null;

			// ✅ Cas spécial : statut NULL -> on autorise EN_ATTENTE et on PERSISTE
			if (($from === null || $from === '') && $to === 'EN_ATTENTE') {
				$patient->setStatut('EN_ATTENTE');
				$patient->setIsOpen(true);
				$em->flush();

				return $this->json([
					'success' => true,
					'data' => ['id' => $patient->getId(), 'statut' => 'EN_ATTENTE'],
					'message' => 'Statut initialisé'
				]);
			}


			// ✅ Remettre en attente autorisé depuis n’importe quel statut
			if ($to !== 'EN_ATTENTE') {
				if (!in_array($to, self::TRANSITIONS[$from] ?? [], true)) {
					return $this->json([
						'success' => false,
						'message' => "Transition non autorisée ($from → $to)",
					], 400);
				}
			}


			$patient->setStatut($to);
			$patient->setIsOpen(true);
			$patient->setIsConsulted(false);

			if ($to === 'EN_CONSULTATION' || $to === 'TERMINE') {
				$patient->setIsConsulted(true);
			}

			$em->flush();





			return $this->json([
				'success' => true,
				'data' => [
					'id' => $patient->getId(),
					'statut' => $to,
				]
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
