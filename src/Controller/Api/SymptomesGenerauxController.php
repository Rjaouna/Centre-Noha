<?php

namespace App\Controller\Api;

use App\Entity\FicheClient;
use App\Entity\SymptomesGeneraux;
use App\Repository\SymptomesGenerauxRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/symptomes-generaux')]
class SymptomesGenerauxController extends AbstractController
{
	#[Route('/{idClient}', name: 'api_symptomes_show', methods: ['GET'])]
	public function show(
		int $idClient,
		SymptomesGenerauxRepository $repo
	): JsonResponse {
		$symptomes = $repo->findOneBy(['client' => $idClient]);

		if (!$symptomes) {
			return new JsonResponse([
				'exists'  => false,
				'message' => 'Aucun symptôme général trouvé pour ce patient.',
			]);
		}

		return new JsonResponse([
			'exists' => true,
			'data'   => [
				'mauxTete'    => $symptomes->getMauxTete(),
				'mauxNuque'   => $symptomes->getMauxNuque(),
				'insomnie'    => $symptomes->getInsomnie(),
				'hemorroides' => $symptomes->getHemorroides(),
				'enuresie'    => $symptomes->getEnuresie(),
			],
		]);
	}

	#[Route('/add/{id}', name: 'api_symptomes_add', methods: ['POST'])]
	public function add(
		FicheClient $client,
		Request $request,
		EntityManagerInterface $em
	): JsonResponse {
		$data = json_decode($request->getContent(), true) ?? [];

		$symptomes = new SymptomesGeneraux();
		$symptomes->setClient($client);
		$symptomes->setMauxTete($data['mauxTete'] ?? null);
		$symptomes->setMauxNuque($data['mauxNuque'] ?? null);
		$symptomes->setInsomnie($data['insomnie'] ?? null);
		$symptomes->setHemorroides($data['hemorroides'] ?? null);
		$symptomes->setEnuresie($data['enuresie'] ?? null);

		$em->persist($symptomes);
		$em->flush();

		return new JsonResponse([
			'success' => true,
			'message' => 'Symptômes généraux enregistrés.',
		]);
	}

	#[Route('/edit/{id}', name: 'api_symptomes_edit', methods: ['POST'])]
	public function edit(
		FicheClient $client,
		Request $request,
		EntityManagerInterface $em,
		SymptomesGenerauxRepository $repo
	): JsonResponse {
		$data = json_decode($request->getContent(), true) ?? [];

		// On récupère les symptômes existants du client
		$symptomes = $repo->findOneBy(['client' => $client]);

		if (!$symptomes) {
			// Sécurité : si ça n’existe pas, on crée (au cas où)
			$symptomes = new SymptomesGeneraux();
			$symptomes->setClient($client);
			$em->persist($symptomes);
		}

		$symptomes->setMauxTete($data['mauxTete'] ?? null);
		$symptomes->setMauxNuque($data['mauxNuque'] ?? null);
		$symptomes->setInsomnie($data['insomnie'] ?? null);
		$symptomes->setHemorroides($data['hemorroides'] ?? null);
		$symptomes->setEnuresie($data['enuresie'] ?? null);

		$em->flush();

		return new JsonResponse([
			'success' => true,
			'message' => 'Symptômes généraux mis à jour.',
		]);
	}
}
