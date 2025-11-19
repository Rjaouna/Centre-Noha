<?php

namespace App\Controller\Api;

use App\Repository\RendezVousRepository;
use App\Repository\FicheClientRepository;
use App\Repository\SymptomesGenerauxRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/statistics')]
class ApiStatisticsController extends AbstractController
{
	#[Route('/maladies', name: 'api_statistics_maladies', methods: ['GET'])]
	public function statsMaladies(FicheClientRepository $repo): JsonResponse
	{
		// Retourne un tableau : [ ['typeMaladie' => 'Aigüe', 'count' => 12], ... ]
		$results = $repo->countAllMaladies();

		$labels = [];
		$values = [];

		foreach ($results as $row) {
			$labels[] = $row['typeMaladie'] ?? 'Inconnu';
			$values[] = $row['count'];
		}

		return new JsonResponse([
			'labels' => $labels,
			'values' => $values
		]);
	}
	#[Route('/symptomes', name: 'api_statistics_symptomes', methods: ['GET'])]
	public function statsSymptomes(SymptomesGenerauxRepository $repo): JsonResponse
	{
		$labels = ["Maux de tête", "Maux de nuque", "Insomnie", "Hémorroïdes", "Énurésie"];

		$values = [
			$repo->countByField("mauxTete"),
			$repo->countByField("mauxNuque"),
			$repo->countByField("insomnie"),
			$repo->countByField("hemorroides"),
			$repo->countByField("enuresie"),
		];

		return new JsonResponse([
			'labels' => $labels,
			'values' => $values
		]);
	}
}
