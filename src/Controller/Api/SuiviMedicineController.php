<?php

namespace App\Controller\Api;

use App\Repository\SuiviRepository;
use App\Repository\MedicineRepository;
use App\Repository\SuiviSoinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class SuiviMedicineController extends AbstractController
{
	#[Route('/api/suivis/{suiviId}/medicines/{medicineId}', name: 'api_suivi_medicine_delete', methods: ['DELETE'])]
	public function delete(
		int $suiviId,
		int $medicineId,
		SuiviSoinRepository $suiviRepository,
		MedicineRepository $medicineRepository,
		EntityManagerInterface $em
	): JsonResponse {
		$suivi = $suiviRepository->find($suiviId);
		if (!$suivi) {
			return $this->json(['success' => false, 'message' => 'Suivi introuvable.'], 404);
		}

		$med = $medicineRepository->find($medicineId);
		if (!$med) {
			return $this->json(['success' => false, 'message' => 'Médicament introuvable.'], 404);
		}

		// ✅ si ManyToMany
		if (method_exists($suivi, 'getMedicines') && !$suivi->getMedicines()->contains($med)) {
			return $this->json(['success' => false, 'message' => "Ce médicament n'est pas lié à ce suivi."], 422);
		}

		// ✅ il faut avoir removeMedicine() dans ton entity Suivi
		$suivi->removeMedicine($med);

		$em->flush();

		return $this->json(['success' => true]);
	}
}
