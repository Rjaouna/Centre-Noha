<?php

namespace App\Controller\Api;

use App\Repository\PatientPrestationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\DBAL\Connection;
use Symfony\Component\Routing\Attribute\Route;

class CaisseApiController extends AbstractController
{
	#[Route('/api/caisse/recette-du-jour', name: 'api_caisse_recette_du_jour', methods: ['GET'])]
	public function recetteDuJour(PatientPrestationRepository $repo): JsonResponse
	{
		$tz = new \DateTimeZone('Europe/Paris');

		$start = (new \DateTimeImmutable('today', $tz))->setTime(0, 0, 0);
		$end   = $start->modify('+1 day');

		$total = $repo->sumRevenueBetween($start, $end);

		// Normalisation "0" => "0.00"
		$totalFormatted = number_format((float) $total, 2, '.', '');

		return $this->json([
			'success' => true,
			'date' => $start->format('Y-m-d'),
			'from' => $start->format(\DateTimeInterface::ATOM),
			'to' => $end->format(\DateTimeInterface::ATOM),
			'total' => $totalFormatted, // string "123.45"
			'currency' => 'DH',
		]);
	}
	#[Route('/api/caisse/cloturer-journee', name: 'api_caisse_cloturer_journee', methods: ['POST'])]
	public function cloturerJournee(Connection $conn): JsonResponse
	{
		// optionnel sécurité (à adapter)
		// $this->denyAccessUnlessGranted('ROLE_USER');

		$conn->beginTransaction();

		try {
			// IMPORTANT: ordre pour éviter FK
			$deletedPivot = $conn->executeStatement('DELETE FROM waiting_room_patient_prestation');
			$deletedWr    = $conn->executeStatement('DELETE FROM waiting_room');
			$deletedPp    = $conn->executeStatement('DELETE FROM patient_prestation');

			$conn->commit();

			return $this->json([
				'success' => true,
				'deleted' => [
					'pivot' => $deletedPivot,
					'waiting_room' => $deletedWr,
					'patient_prestation' => $deletedPp,
				],
			]);
		} catch (\Throwable $e) {
			$conn->rollBack();

			return $this->json([
				'success' => false,
				'message' => $e->getMessage(),
			], 500);
		}
	}
}
