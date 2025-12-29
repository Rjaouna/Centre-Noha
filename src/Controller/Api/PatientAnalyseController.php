<?php

namespace App\Controller\Api;

use App\Entity\FicheClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/patient')]
class PatientAnalyseController extends AbstractController
{
	#[Route('/{id}/analyse-prescriptions', methods: ['GET'], name: 'api_patient_analyse_prescriptions')]
	public function analysePrescriptionsPartial(
		FicheClient $patient
	): Response {
		return $this->render(
			'fiche_client/_composants/_analyse_prescriptions.html.twig',
			[
				'prescriptions' => $patient->getAnalysePrescriptions()
			]
		);
	}
}
