<?php

namespace App\Controller\Medicine;

use App\Entity\Medicine;
use App\Repository\MedicineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
	#[Route('/medicine/{id}', name: 'medicine_show')]
	public function show(Medicine $medicine): Response
	{
		return $this->render('medicine/show.html.twig', [
			'medicine' => $medicine,
		]);
	}
}
