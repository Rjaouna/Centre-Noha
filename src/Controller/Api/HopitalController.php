<?php

namespace App\Controller\Api;

use App\Repository\HopitalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HopitalController extends AbstractController
{
	#[Route('/hopital/search/api', name: 'hopital_search_api')]
	public function search(Request $request, HopitalRepository $repo): JsonResponse
	{
		$city = $request->query->get('city', '');

		if (strlen($city) < 2) {
			return new JsonResponse([]);
		}

		$results = $repo->searchByCity($city);

		return new JsonResponse($results);
	}
}
