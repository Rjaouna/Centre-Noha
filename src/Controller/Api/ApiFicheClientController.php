<?php

namespace App\Controller\Api;

use App\Entity\FicheClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/fiche')]
class ApiFicheClientController extends AbstractController
{
	#[Route('/edit/{id}', name: 'api_fiche_edit', methods: ['POST'])]
	public function editFiche(
		FicheClient $client,
		Request $request,
		EntityManagerInterface $em
	): JsonResponse {

		$data = json_decode($request->getContent(), true);

		if (!$data) {
			return new JsonResponse([
				'success' => false,
				'message' => 'Aucune donnée reçue'
			], 400);
		}

		// 🔵 Mise à jour des champs de la fiche
		$client->setNom($data['nom'] ?? $client->getNom());
		$client->setVille($data['ville'] ?? $client->getVille());
		$client->setTelephone($data['telephone'] ?? $client->getTelephone());
		$client->setAge($data['age'] ?? $client->getAge());
		$client->setPoids($data['poids'] ?? $client->getPoids());
		$client->setDureeMaladie($data['dureeMaladie'] ?? $client->getDureeMaladie());
		$client->setTypeMaladie($data['typeMaladie'] ?? $client->getTypeMaladie());
		$client->setTraitement($data['traitement'] ?? $client->getTraitement());
		$client->setObservation($data['observation'] ?? $client->getObservation());

		$em->flush();

		return new JsonResponse([
			'success' => true,
			'message' => 'Fiche patient mise à jour'
		]);
	}
}
