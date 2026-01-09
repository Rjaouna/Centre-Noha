<?php

namespace App\Controller\Api;

use App\Entity\PrestationPrice;
use App\Repository\PrestationPriceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/prestation-prices')]
class PrestationPriceApiController extends AbstractController
{
	#[Route('', methods: ['GET'])]
	public function list(PrestationPriceRepository $repo): JsonResponse
	{
		$items = $repo->findBy([], ['id' => 'DESC']);

		$data = array_map(fn(PrestationPrice $p) => [
			'id' => $p->getId(),
			'nom' => $p->getNom(),
			'categorie' => $p->getCategorie(),
			'prix' => $p->getPrix(), // decimal -> string
			'duree_minutes' => $p->getDureeMinutes(),
			'description' => $p->getDescription(),
		], $items);

		return $this->json($data);
	}

	#[Route('/{id}', methods: ['GET'])]
	public function show(PrestationPrice $p): JsonResponse
	{
		return $this->json([
			'id' => $p->getId(),
			'nom' => $p->getNom(),
			'categorie' => $p->getCategorie(),
			'prix' => $p->getPrix(),
			'duree_minutes' => $p->getDureeMinutes(),
			'description' => $p->getDescription(),
		]);
	}

	#[Route('', methods: ['POST'])]
	public function create(Request $request, EntityManagerInterface $em): JsonResponse
	{
		$payload = json_decode($request->getContent(), true) ?? [];

		$errors = $this->validatePayload($payload);
		if ($errors) {
			return $this->json(['errors' => $errors], 422);
		}

		$p = new PrestationPrice();
		$this->hydrate($p, $payload);

		$em->persist($p);
		$em->flush();

		return $this->json(['success' => true, 'id' => $p->getId()], 201);
	}

	#[Route('/{id}', methods: ['PUT'])]
	public function update(Request $request, PrestationPrice $p, EntityManagerInterface $em): JsonResponse
	{
		$payload = json_decode($request->getContent(), true) ?? [];

		$errors = $this->validatePayload($payload);
		if ($errors) {
			return $this->json(['errors' => $errors], 422);
		}

		$this->hydrate($p, $payload);
		$em->flush();

		return $this->json(['success' => true, 'id' => $p->getId()]);
	}

	#[Route('/{id}', methods: ['DELETE'])]
	public function delete(PrestationPrice $p, EntityManagerInterface $em): JsonResponse
	{
		$id = $p->getId();
		$em->remove($p);
		$em->flush();

		return $this->json(['success' => true, 'id' => $id]);
	}

	private function hydrate(PrestationPrice $p, array $payload): void
	{
		$p->setNom(trim((string)($payload['nom'] ?? '')));
		$p->setCategorie((string)($payload['categorie'] ?? ''));

		// prix : on garde string "25.00" (OK pour decimal)
		$prix = str_replace(',', '.', (string)($payload['prix'] ?? '0'));
		$p->setPrix($prix);

		$duree = (float)($payload['duree_minutes'] ?? 0);
		$p->setDureeMinutes($duree);

		$desc = trim((string)($payload['description'] ?? ''));
		$p->setDescription($desc !== '' ? $desc : null);
	}

	private function validatePayload(array $payload): array
	{
		$errors = [];

		$nom = trim((string)($payload['nom'] ?? ''));
		$categorie = trim((string)($payload['categorie'] ?? ''));
		$prixRaw = (string)($payload['prix'] ?? '');
		$dureeRaw = $payload['duree_minutes'] ?? null;

		if ($nom === '') $errors[] = "Le nom est obligatoire.";
		if ($categorie === '') $errors[] = "La catégorie est obligatoire.";

		$prix = str_replace(',', '.', $prixRaw);
		if ($prix === '' || !is_numeric($prix) || (float)$prix < 0) {
			$errors[] = "Le prix est invalide.";
		}

		if ($dureeRaw === null || $dureeRaw === '' || !is_numeric($dureeRaw) || (float)$dureeRaw < 0) {
			$errors[] = "La durée est invalide.";
		}

		return $errors;
	}
}
