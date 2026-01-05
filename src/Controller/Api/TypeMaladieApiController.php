<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\TypeMaladie;
use App\Repository\TypeMaladieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/type-maladies', name: 'api_type_maladies_')]
class TypeMaladieApiController extends AbstractController
{
	public function __construct(
		private readonly EntityManagerInterface $em,
		private readonly TypeMaladieRepository $repo,
	) {}

	#[Route('', name: 'index', methods: ['GET'])]
	public function index(Request $request): JsonResponse
	{
		$q = trim((string) $request->query->get('q', ''));

		$qb = $this->repo->createQueryBuilder('t')->orderBy('t.nom', 'ASC');
		if ($q !== '') {
			$qb->andWhere('t.nom LIKE :q')->setParameter('q', '%' . $q . '%');
		}

		$items = $qb->getQuery()->getResult();

		$out = array_map(fn(TypeMaladie $t) => [
			'id' => $t->getId(),
			'nom' => $t->getNom(),
		], $items);

		return $this->json($out, 200);
	}

	#[Route('/{id<\d+>}', name: 'show', methods: ['GET'])]
	public function show(int $id): JsonResponse
	{
		$t = $this->repo->find($id);
		if (!$t) return $this->json(['message' => 'Type introuvable.'], 404);

		return $this->json(['id' => $t->getId(), 'nom' => $t->getNom()], 200);
	}

	#[Route('', name: 'create', methods: ['POST'])]
	public function create(Request $request): JsonResponse
	{
		$data = $this->decode($request);
		if ($data === null) return $this->json(['message' => 'JSON invalide.'], 400);

		$nom = trim((string)($data['nom'] ?? ''));
		if ($nom === '') return $this->json(['errors' => ['nom' => ['Champ obligatoire.']]], 422);
		if (mb_strlen($nom) > 20) return $this->json(['errors' => ['nom' => ['Max 20 caractères.']]], 422);

		$t = new TypeMaladie();
		$t->setNom($nom);

		$this->em->persist($t);
		$this->em->flush();

		return $this->json(['id' => $t->getId(), 'nom' => $t->getNom()], 201);
	}

	#[Route('/{id<\d+>}', name: 'update', methods: ['PUT'])]
	public function update(int $id, Request $request): JsonResponse
	{
		$t = $this->repo->find($id);
		if (!$t) return $this->json(['message' => 'Type introuvable.'], 404);

		$data = $this->decode($request);
		if ($data === null) return $this->json(['message' => 'JSON invalide.'], 400);

		$nom = trim((string)($data['nom'] ?? ''));
		if ($nom === '') return $this->json(['errors' => ['nom' => ['Champ obligatoire.']]], 422);
		if (mb_strlen($nom) > 20) return $this->json(['errors' => ['nom' => ['Max 20 caractères.']]], 422);

		$t->setNom($nom);
		$this->em->flush();

		return $this->json(['id' => $t->getId(), 'nom' => $t->getNom()], 200);
	}

	#[Route('/{id<\d+>}', name: 'patch', methods: ['PATCH'])]
	public function patch(int $id, Request $request): JsonResponse
	{
		$t = $this->repo->find($id);
		if (!$t) return $this->json(['message' => 'Type introuvable.'], 404);

		$data = $this->decode($request);
		if ($data === null) return $this->json(['message' => 'JSON invalide.'], 400);

		if (array_key_exists('nom', $data)) {
			$nom = trim((string)$data['nom']);
			if ($nom === '') return $this->json(['errors' => ['nom' => ['Champ obligatoire.']]], 422);
			if (mb_strlen($nom) > 20) return $this->json(['errors' => ['nom' => ['Max 20 caractères.']]], 422);
			$t->setNom($nom);
		}

		$this->em->flush();
		return $this->json(['id' => $t->getId(), 'nom' => $t->getNom()], 200);
	}

	#[Route('/{id<\d+>}', name: 'delete', methods: ['DELETE'])]
	public function delete(int $id): JsonResponse
	{
		$t = $this->repo->find($id);
		if (!$t) return $this->json(['message' => 'Type introuvable.'], 404);

		$this->em->remove($t);
		$this->em->flush();

		return $this->json(['message' => 'Type supprimé.'], 200);
	}

	private function decode(Request $request): ?array
	{
		$raw = $request->getContent();
		if ($raw === '') return [];
		try {
			return json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
		} catch (\Throwable) {
			return null;
		}
	}
}
