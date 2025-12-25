<?php

namespace App\Controller\Api;

use App\Repository\SymptomeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Symptome;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api/symptomes', name: 'api_symptomes_')]
class SymptomeController extends AbstractController
{

	#[Route('/search', name: 'search', methods: ['GET'])]
	public function search(Request $request, SymptomeRepository $repo): JsonResponse
	{
		$q = $request->query->get('q');

		if (!$q || strlen($q) < 2) {
			return new JsonResponse([]);
		}

		$symptomes = $repo->createQueryBuilder('s')
			->where('LOWER(s.name) LIKE :q')
			->setParameter('q', '%' . mb_strtolower($q) . '%')
			->setMaxResults(10)
			->getQuery()
			->getResult();

		$data = array_map(static fn($s) => [
			'id' => $s->getId(),
			'name' => $s->getName(),
			'category' => $s->getCategory(),
		], $symptomes);

		return new JsonResponse($data);
	}
	#[Route('', methods: ['GET'])]
	public function index(SymptomeRepository $repo): JsonResponse
	{
		$symptomes = $repo->findBy([], ['category' => 'ASC', 'name' => 'ASC']);

		return $this->json(array_map(fn(Symptome $s) => [
			'id' => $s->getId(),
			'name' => $s->getName(),
			'category' => $s->getCategory(),
			'description' => $s->getDescription(),

			// ⭐ NOUVEAU
			'hasTraitement' => !$s->getTraitements()->isEmpty(),
			'traitementCount' => $s->getTraitements()->count(),
		], $symptomes));
	}

	#[Route('', methods: ['POST'])]
	public function create(
		Request $request,
		EntityManagerInterface $em
	): JsonResponse {
		$data = json_decode($request->getContent(), true);

		if (
			empty($data['name']) ||
			empty($data['category'])
		) {
			return $this->json([
				'success' => false,
				'message' => 'Nom et catégorie obligatoires'
			], 400);
		}

		$symptome = new Symptome();
		$symptome->setName($data['name']);
		$symptome->setCategory($data['category']);
		$symptome->setDescription($data['description'] ?? null);

		$em->persist($symptome);
		$em->flush();

		return $this->json([
			'success' => true,
			'id' => $symptome->getId()
		]);
	}

	#[Route('/{id}', methods: ['GET'])]
	public function show(Symptome $symptome): JsonResponse
	{
		return $this->json([
			'id' => $symptome->getId(),
			'name' => $symptome->getName(),
			'category' => $symptome->getCategory(),
			'description' => $symptome->getDescription(),
		]);
	}

	#[Route('/{id}', methods: ['PUT'])]
	public function update(
		Symptome $symptome,
		Request $request,
		EntityManagerInterface $em
	): JsonResponse {
		$data = json_decode($request->getContent(), true);

		if (isset($data['name'])) {
			$symptome->setName($data['name']);
		}

		if (isset($data['category'])) {
			$symptome->setCategory($data['category']);
		}

		if (array_key_exists('description', $data)) {
			$symptome->setDescription($data['description']);
		}

		$em->flush();

		return $this->json(['success' => true]);
	}

	#[Route('/{id}', methods: ['DELETE'])]
	public function delete(
		Symptome $symptome,
		EntityManagerInterface $em
	): JsonResponse {
		$em->remove($symptome);
		$em->flush();

		return $this->json(['success' => true]);
	}

	
}
