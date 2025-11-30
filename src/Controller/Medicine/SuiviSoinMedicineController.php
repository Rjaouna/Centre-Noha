<?php

namespace App\Controller\Medicine;

use App\Entity\Medicine;
use App\Entity\SuiviSoin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SuiviSoinMedicineController extends AbstractController
{
	/**
	 * 🔵 Ajout d’un médicament à un suivi (via API / AJAX)
	 */
	#[Route('/suivi/{id}/medicine/add', name: 'suivi_add_medicine', methods: ['POST'])]
	public function addMedicine(
		SuiviSoin $suivi,
		Request $request,
		EntityManagerInterface $em
	): JsonResponse {

		$data = json_decode($request->getContent(), true);

		if (!$data || !isset($data['medicine_id'])) {
			return new JsonResponse(['error' => 'Aucun médicament reçu'], 400);
		}

		$medicineId = $data['medicine_id'];
		$medicine = $em->getRepository(Medicine::class)->find($medicineId);

		if (!$medicine) {
			return new JsonResponse(['error' => 'Médicament introuvable'], 404);
		}

		// Si déjà présent → ignorer
		if ($suivi->getMedicine()->contains($medicine)) {
			return new JsonResponse(['error' => 'Ce médicament est déjà ajouté'], 409);
		}

		// Ajout ManyToMany
		$suivi->addMedicine($medicine);

		$em->persist($suivi);
		$em->flush();

		return new JsonResponse([
			'success' => true,
			'message' => 'Médicament ajouté avec succès',
			'medicine' => [
				'id' => $medicine->getId(),
				'name' => $medicine->getName(),
				'code' => $medicine->getCode(),
				'dci' => $medicine->getDci(),
				'dosage' => $medicine->getDosage(),
				'uniteDosage' => $medicine->getUniteDosage()
			]
		]);
	}


	/**
	 * 🔵 Suppression d’un médicament d’un suivi (facultatif)
	 */
	#[Route('/suivi/{id}/medicine/remove', name: 'suivi_remove_medicine', methods: ['POST'])]
	public function removeMedicine(
		SuiviSoin $suivi,
		Request $request,
		EntityManagerInterface $em
	): JsonResponse {

		$data = json_decode($request->getContent(), true);

		if (!$data || !isset($data['medicine_id'])) {
			return new JsonResponse(['error' => 'Aucun médicament reçu'], 400);
		}

		$medicine = $em->getRepository(Medicine::class)->find($data['medicine_id']);

		if (!$medicine) {
			return new JsonResponse(['error' => 'Médicament introuvable'], 404);
		}

		if (!$suivi->getMedicine()->contains($medicine)) {
			return new JsonResponse(['error' => 'Ce médicament n’est pas lié'], 409);
		}

		$suivi->removeMedicine($medicine);

		$em->persist($suivi);
		$em->flush();

		return new JsonResponse([
			'success' => true,
			'message' => 'Médicament retiré du suivi'
		]);
	}
}
