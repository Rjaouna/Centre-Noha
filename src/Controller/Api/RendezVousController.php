<?php

namespace App\Controller\Api;

use App\Entity\RendezVous;
use App\Repository\FicheClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RendezVousController extends AbstractController
{
	/**
	 * 🔹 Création d’un rendez-vous
	 * POST /api/rendezvous
	 */
	#[Route('/api/rendezvous', name: 'api_rendezvous_create', methods: ['POST'])]
	public function create(
		Request $request,
		EntityManagerInterface $em,
		FicheClientRepository $clientRepo
	): JsonResponse {

		/* ==========================
         * 🔐 Sécurité : praticien
         * ========================== */
		$praticien = $this->getUser();
		if (!$praticien) {
			return $this->json([
				'success' => false,
				'message' => 'Utilisateur non authentifié'
			], 401);
		}

		/* ==========================
         * 📥 Lecture JSON
         * ========================== */
		$data = json_decode($request->getContent(), true);

		if (
			!isset($data['patient']) ||
			!isset($data['date']) ||
			!isset($data['heure'])
		) {
			return $this->json([
				'success' => false,
				'message' => 'Données manquantes'
			], 400);
		}

		/* ==========================
         * 👤 Patient
         * ========================== */
		$patient = $clientRepo->find($data['patient']);
		if (!$patient) {
			return $this->json([
				'success' => false,
				'message' => 'Patient introuvable'
			], 404);
		}

		/* ==========================
         * 🕒 Dates & heures (DateTime)
         * ========================== */
		try {
			$date = new \DateTime($data['date']);
			$heureDebut = new \DateTime($data['date'] . ' ' . $data['heure']);
			$heureFin = (clone $heureDebut)->modify('+20 minutes');
		} catch (\Exception) {
			return $this->json([
				'success' => false,
				'message' => 'Date ou heure invalide'
			], 400);
		}

		/* ==========================
         * 🚫 Anti double réservation
         * ========================== */
		$existing = $em->createQuery(
			'SELECT r.id FROM App\Entity\RendezVous r
             WHERE r.praticien = :praticien
             AND r.date = :date
             AND r.heureDebut = :heure
             AND r.statut != :annule'
		)
			->setParameter('praticien', $praticien)
			->setParameter('date', $date)
			->setParameter('heure', $heureDebut)
			->setParameter('annule', 'annule')
			->setMaxResults(1)
			->getOneOrNullResult();

		if ($existing) {
			return $this->json([
				'success' => false,
				'message' => 'Ce créneau est déjà réservé'
			], 409);
		}

		/* ==========================
         * ✅ Création RDV
         * ========================== */
		try {
			$rdv = new RendezVous();
			$rdv->setPraticien($praticien);
			$rdv->setPatient($patient);
			$rdv->setDate($date);
			$rdv->setHeureDebut($heureDebut);
			$rdv->setHeureFin($heureFin);
			$rdv->setStatut('confirme');
			$rdv->setMotif($data['motif'] ?? null);
			$rdv->setCreatedAt(new \DateTimeImmutable());
			$rdv->setExpiresAt(
				\DateTimeImmutable::createFromMutable($heureDebut)
			);


			$em->persist($rdv);
			$em->flush();

			return $this->json([
				'success' => true,
				'rdvId'   => $rdv->getId()
			], 201);
		} catch (\Throwable $e) {

			// ⚠️ En DEV uniquement (à enlever en prod)
			return $this->json([
				'success' => false,
				'message' => $e->getMessage()
			], 500);
		}
	}
}
