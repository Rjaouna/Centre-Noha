<?php

namespace App\Controller\Hopital;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\HopitalRepository;
use Symfony\Component\HttpFoundation\Request;

class HopitalController extends AbstractController
{
	#[Route('/hopital/recherche', name: 'hopital_search_page')]
	public function searchPage(): Response
	{
		return $this->render('hopital/search.html.twig');
	}

	#[Route('/hopital/search/api', name: 'hopital_search_api')]
	public function searchApi(Request $request, HopitalRepository $repo)
	{
		$city = $request->query->get('city', '');

		if (strlen($city) < 2) {
			return $this->json([]);
		}

		$results = $repo->searchByCity($city);

		return $this->json($results);
	}

	#[Route('/hopital/{id}', name: 'hopital_show')]
	public function show(int $id, HopitalRepository $repo): Response
	{
		$hopital = $repo->find($id);

		if (!$hopital) {
			throw $this->createNotFoundException("Établissement introuvable");
		}

		return $this->render('hopital/show.html.twig', [
			'hopital' => $hopital
		]);
	}
}
