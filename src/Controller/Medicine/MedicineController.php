<?php

namespace App\Controller\Medicine;

use App\Entity\Medicine;
use App\Repository\MedicineRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MedicineController extends AbstractController
{
	#[Route('/medicine', name: 'medicine_index')]
	public function index(MedicineRepository $medicineRepository): Response
	{
		$medicines = $medicineRepository->findAll();

		return $this->render('medicine/index.html.twig', [
			'medicines' => $medicines,
		]);
	}
	#[Route('/medicine/search/api', name: 'medicine_api_search', methods: ['GET'])]
	public function search(Request $request, MedicineRepository $repo): JsonResponse
	{
		$term = $request->query->get('search', '');

		$results = $repo->searchLimited($term);

		return new JsonResponse($results);
	}
	#[Route('/medicine/{id}', name: 'medicine_show')]
	public function show(Medicine $medicine): Response
	{
		return $this->render('medicine/show.html.twig', [
			'medicine' => $medicine,
		]);
	}
}
