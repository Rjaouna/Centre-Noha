<?php

namespace App\Controller\Api;

use DateTimeImmutable;
use App\Repository\FicheClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/notifications', name: 'api_notifications_')]
class NotificationController extends AbstractController
{
	#[Route('/feeds', name: 'feeds', methods: ['GET'])]
	public function feeds(
		FicheClientRepository $ficheRepo,
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


		/** 3️⃣ Format JSON */

		$clientsFormatted = array_map(fn($c) => [
			'id' => $c->getId(),
			'nom' => $c->getNom(),
			'ville' => $c->getVille(),
			'telephone' => $c->getTelephone(),
			'createdAt' => $c->getCreatedAt()?->format('Y-m-d H:i'),
		], $newClients);


		return $this->json([
			'newClients' => $clientsFormatted,
		]);
	}
}
