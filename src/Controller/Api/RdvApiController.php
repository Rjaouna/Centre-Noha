<?php

namespace App\Controller\Api;

use App\Entity\RendezVous;
use App\Repository\FicheClientRepository;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/rdv', name: 'api_rdv_')]
class RdvApiController extends AbstractController
{
	#[Route('/disponibilites/{date}', name: 'disponibilites', methods: ['GET'])]
	public function disponibilites(string $date, RendezVousRepository $repo): JsonResponse
	{
		$start = new \DateTimeImmutable('09:00');
		$end   = new \DateTimeImmutable('18:00');
		$interval = new \DateInterval('PT30M');

		$today = new \DateTimeImmutable('today');
		$now   = new \DateTimeImmutable();

		$isToday = ($today->format('Y-m-d') === $date);

		$creneaux = [];

		for ($time = $start; $time <= $end; $time = $time->add($interval)) {

			// Sauter les créneaux passés si c'est aujourd'hui
			if ($isToday) {
				$dateTimeSlot = new \DateTimeImmutable("$date " . $time->format('H:i'));

				if ($dateTimeSlot <= $now) {
					continue;
				}
			}

			$dateTime = new \DateTimeImmutable("$date " . $time->format('H:i'));

			$rdv = $repo->findOneBy(['dateRdvAt' => $dateTime]);

			$creneaux[] = [
				'heure' => $time->format('H:i'),
				'disponible' => $rdv ? false : true
			];
		}

		return new JsonResponse($creneaux);
	}

	#[Route('/reserver', name: 'reserver', methods: ['POST'])]
	public function reserver(
		Request $request,
		FicheClientRepository $clientRepo,
		RendezVousRepository $repo,
		EntityManagerInterface $em
	): JsonResponse {
		$data = json_decode($request->getContent(), true);

		$clientId = $data['patient'] ?? null;
		$date     = $data['date'] ?? null;
		$heure    = $data['heure'] ?? null;
		$motif    = $data['motif'] ?? null;

		if (!$clientId || !$date || !$heure) {
			return new JsonResponse(['error' => 'Données invalides'], 400);
		}

		$client = $clientRepo->find($clientId);
		if (!$client) {
			return new JsonResponse(['error' => 'Client introuvable'], 404);
		}

		$dateTime = new \DateTimeImmutable("$date $heure");

		// vérif disponibilité
		if ($repo->findOneBy(['dateRdvAt' => $dateTime])) {
			return new JsonResponse(['error' => 'Créneau déjà réservé'], 409);
		}

		$rdv = new RendezVous();
		$rdv->setClient($client);
		$rdv->setDateRdvAt($dateTime);
		$rdv->setMotif($motif);
		$rdv->setStatut('A venir');

		$em->persist($rdv);
		$em->flush();

		return new JsonResponse([
			'success' => true,
			'id'      => $rdv->getId(),
		]);
	}

	#[Route('/day/{day}', name: 'day', methods: ['GET'])]
	public function rdvByDay(string $day, RendezVousRepository $repo): JsonResponse
	{
		$rdvs = $repo->findAll();
		$result = [];

		foreach ($rdvs as $r) {
			if ($r->getDateRdvAt() && $r->getDateRdvAt()->format('D') === $day) {

				$statut = $r->getStatut() ?? 'A venir';
				$statutColor = [
					'A venir' => 'success',
					'Annulé'  => 'warning',
					'Passé'   => 'danger',
				][$statut] ?? 'secondary';

				$result[] = [
					'id'          => $r->getId(),
					'clientId'    => $r->getClient()->getId(),
					'client'      => $r->getClient()->getNom(),
					'heure'       => $r->getDateRdvAt()->format('H:i'),
					'motif'       => $r->getMotif(),
					'statut'      => $statut,
					'statutColor' => $statutColor,
				];
			}
		}

		return new JsonResponse($result);
	}

	#[Route('/validate/{id}', name: 'validate', methods: ['POST'])]
	public function validateRdv(RendezVous $rdv, EntityManagerInterface $em): JsonResponse
	{
		$rdv->setStatut('Passé');
		$em->flush();

		return new JsonResponse([
			'success'   => true,
			'message'   => 'Rendez-vous validé',
			'newStatus' => $rdv->getStatut(),
		]);
	}
}
