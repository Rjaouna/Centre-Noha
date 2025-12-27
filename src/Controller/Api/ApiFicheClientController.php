<?php

namespace App\Controller\Api;

use App\Entity\FicheClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/fiche')]
class ApiFicheClientController extends AbstractController
{
	#[Route('/edit/{id}', name: 'api_fiche_edit', methods: ['POST'])]
	public function edit(
		FicheClient $client,
		Request $request,
		EntityManagerInterface $em
	): JsonResponse {

		$data = json_decode($request->getContent(), true);

		if (!$data) {
			return $this->json([
				'success' => false,
				'message' => 'Aucune donnée reçue'
			], 400);
		}

		// 🔐 Champs autorisés
		$fields = [
			'nom'          => 'setNom',
			'ville'        => 'setVille',
			'telephone'    => 'setTelephone',
			'poids'        => 'setPoids',
			'typeMaladie'  => 'setTypeMaladie',
			'traitement'   => 'setTraitement',
			'observation'  => 'setObservation',
			'isOpen'       => 'setIsOpen',
			'isConsulted'  => 'setIsConsulted',
		];

		foreach ($fields as $key => $setter) {
			if (array_key_exists($key, $data)) {
				$client->$setter($data[$key] ?: null);
			}
		}

		// 📅 DATE DE NAISSANCE → ÂGE AUTO
		if (array_key_exists('dateNaissance', $data)) {

			if ($data['dateNaissance']) {

				$dateNaissance = new \DateTimeImmutable($data['dateNaissance']);
				$client->setDateNaissance($dateNaissance);

				// 🧮 Calcul âge
				$today = new \DateTimeImmutable();
				$diff = $today->diff($dateNaissance);

				// On stocke une date représentant l'âge
				$client->setAge(
					$today->sub(new \DateInterval('P' . $diff->y . 'Y'))
				);
			} else {
				// Suppression date
				$client->setDateNaissance(null);
				$client->setAge(null);
			}
		}

		$em->flush();

		return $this->json([
			'success' => true,
			'message' => 'Fiche patient mise à jour'
		]);
	}
}
