<?php

namespace App\Controller\Api;

use DateTimeImmutable;
use App\Repository\FicheClientRepository;
use App\Repository\RendezVousRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/notifications', name: 'api_notifications_')]
class NotificationController extends AbstractController
{
	#[Route('/feeds', name: 'feeds', methods: ['GET'])]
	public function feeds(
		FicheClientRepository $ficheRepo,
		RendezVousRepository $rdvRepo
	): JsonResponse {

		/** 1️⃣ Nouveaux inscrits non consultés */
		$newClients = $ficheRepo->createQueryBuilder('f')
			->where('f.isConsulted IS NULL OR f.isConsulted = false')
			->orderBy('f.createdAt', 'DESC')
			->setMaxResults(20)
			->getQuery()
			->getResult();

		/** 2️⃣ RDV du jour */
		$today = new DateTimeImmutable('today');
		$tomorrow = $today->modify('+1 day');

		$rdvToday = $rdvRepo->createQueryBuilder('r')
			->where('r.dateRdvAt >= :today AND r.dateRdvAt < :tomorrow AND r.statut = :statut')
			->setParameter('today', $today)
			->setParameter('statut', 'A venir')
			->setParameter('tomorrow', $tomorrow)
			->orderBy('r.dateRdvAt', 'ASC')
			->getQuery()
			->getResult();

		/** 3️⃣ Format JSON */

		$clientsFormatted = array_map(fn($c) => [
			'id' => $c->getId(),
			'nom' => $c->getNom(),
			'ville' => $c->getVille(),
			'telephone' => $c->getTelephone(),
			'createdAt' => $c->getCreatedAt()?->format('Y-m-d H:i'),
		], $newClients);

		$rdvFormatted = array_map(fn($r) => [
			'id' => $r->getId(),
			'client' => $r->getClient()?->getNom(),
			'heure' => $r->getDateRdvAt()?->format('H:i'),
			'motif' => $r->getMotif(),
			'statut' => $r->getStatut(),
		], $rdvToday);

		return $this->json([
			'newClients' => $clientsFormatted,
			'rdvToday' => $rdvFormatted,
			'total' => count($clientsFormatted) + count($rdvFormatted)
		]);
	}
}
