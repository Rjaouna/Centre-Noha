<?php

namespace App\Controller\Api;

use App\Entity\AnalysePrescription;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api')]
class AnalysePrescriptionApiController extends AbstractController
{
	#[Route('/analyse-prescription/{id}', methods: ['DELETE'], name: 'api_analyse_prescription_delete')]
	public function delete(
		AnalysePrescription $prescription,
		EntityManagerInterface $em,
		Request $request
	): JsonResponse {

		// (optionnel) sécurité rôle
		// $this->denyAccessUnlessGranted('ROLE_MEDECIN');

		$em->remove($prescription);
		$em->flush();

		return $this->json([
			'success' => true
		]);
	}

	#[Route(
		'/analyse-prescription/{id}/compte-rendu',
		methods: ['POST']
	)]
	public function saveCompteRendu(
		AnalysePrescription $prescription,
		Request $request,
		EntityManagerInterface $em
	): JsonResponse {

		$data = json_decode($request->getContent(), true);
		$compteRendu = trim($data['compteRendu'] ?? '');

		if ($compteRendu === '') {
			return $this->json([
				'success' => false,
				'message' => 'Compte rendu vide'
			], 400);
		}

		$prescription->setCompteRendu($compteRendu);
		$prescription->setStatus('recue');
		$prescription->setReceivedAt(new \DateTimeImmutable());

		$em->flush();

		return $this->json(['success' => true]);
	}
}
