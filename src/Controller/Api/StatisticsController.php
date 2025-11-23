<?php

namespace App\Controller\Api;

use App\Repository\PaiementRepository;
use App\Repository\FicheClientRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/statistics', name: 'api_statistics_')]
class StatisticsController extends AbstractController
{
	#[Route('/clients-per-day', name: 'clients_per_day', methods: ['GET'])]
	public function clientsPerDay(FicheClientRepository $ficheRepo): JsonResponse
	{
		// Jours de la semaine (format anglais => français)
		$days = [
			'Monday' => 'Lundi',
			'Tuesday' => 'Mardi',
			'Wednesday' => 'Mercredi',
			'Thursday' => 'Jeudi',
			'Friday' => 'Vendredi',
			'Saturday' => 'Samedi',
			'Sunday' => 'Dimanche',
		];

		// Initialiser les compteurs
		$counts = array_fill_keys(array_keys($days), 0);

		// Début et fin de la semaine actuelle
		$startOfWeek = new \DateTime('monday this week');
		$endOfWeek   = new \DateTime('sunday this week 23:59:59');

		// Récupérer toutes les fiches modifiées dans la semaine
		$fiches = $ficheRepo->createQueryBuilder('f')
			->where('f.updatedAt BETWEEN :start AND :end')
			->setParameter('start', $startOfWeek)
			->setParameter('end', $endOfWeek)
			->orderBy('f.updatedAt', 'ASC')
			->getQuery()
			->getResult();

		// Compter par jour
		foreach ($fiches as $fiche) {
			$day = $fiche->getUpdatedAt()->format('l'); // Monday, Tuesday...
			if (isset($counts[$day])) {
				$counts[$day]++;
			}
		}

		return $this->json([
			'labels' => array_values($days),
			'values' => array_values($counts),
		]);
	}
	#[Route('/paiements', name: 'paiements', methods: ['GET'])]
	public function paiementsStats(PaiementRepository $repo): JsonResponse
	{
		$data = $repo->countPaiementsByType();

		return $this->json([
			'labels' => array_column($data, 'type'),
			'values' => array_column($data, 'total')
		]);
	}
}
