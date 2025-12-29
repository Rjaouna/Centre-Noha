<?php

namespace App\Controller;

use App\Entity\AnalysePrescription;
use App\Repository\CabinetRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnalysePrescriptionController extends AbstractController
{
	#[Route(
		'/analyse/prescription/{id}/pdf',
		name: 'analyse_prescription_pdf',
		methods: ['GET']
	)]
	public function pdf(AnalysePrescription $prescription, CabinetRepository $cabinetRepository): Response
	{
		return $this->render('analyse_prescription/pdf.html.twig', [
			'prescription' => $prescription,
			'patient' => $prescription->getPatient(),
			'medecin' => $this->getUser(),
			'cabinet' => $cabinetRepository->findOneBy([]),
		]);
	}
}
