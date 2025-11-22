<?php

namespace App\Controller\Api;

use App\Entity\SuiviSoin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SuiviDeleteController extends AbstractController
{
	#[Route('/api/suivi/{id}/delete', name: 'api_suivi_delete', methods: ['DELETE'])]
	public function delete(
		?SuiviSoin $suiviSoin,   // 👈 paramètre en camelCase
		EntityManagerInterface $em
	): JsonResponse {

		// Vérifier existence
		if (!$suiviSoin) {
			return $this->json([
				'success' => false,
				'message' => 'Suivi introuvable.'
			], 404);
		}

		try {
			$em->remove($suiviSoin);
			$em->flush();

			return $this->json([
				'success' => true,
				'message' => 'Suivi supprimé.'
			]);
		} catch (\Exception $e) {

			return $this->json([
				'success' => false,
				'message' => 'Erreur serveur : ' . $e->getMessage()
			], 500);
		}
	}
}
