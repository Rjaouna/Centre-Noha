<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\VilleMaroc;
use App\Repository\VilleMarocRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/villes', name: 'api_villes_')]
class VilleMarocApiController extends AbstractController
{
	public function __construct(
		private readonly EntityManagerInterface $em,
		private readonly VilleMarocRepository $repo,
	) {}

	#[Route('', name: 'index', methods: ['GET'])]
	public function index(Request $request): JsonResponse
	{
		$q = trim((string) $request->query->get('q', ''));

		$qb = $this->repo->createQueryBuilder('v')->orderBy('v.nom', 'ASC');
		if ($q !== '') {
			$qb->andWhere('v.nom LIKE :q')->setParameter('q', '%' . $q . '%');
		}

		$items = $qb->getQuery()->getResult();

		$out = array_map(fn(VilleMaroc $v) => [
			'id' => $v->getId(),
			'nom' => $v->getNom(),
			'region' => $v->getRegion(),
		], $items);

		return $this->json($out, 200);
	}

	#[Route('/{id<\d+>}', name: 'show', methods: ['GET'])]
	public function show(int $id): JsonResponse
	{
		$v = $this->repo->find($id);
		if (!$v) return $this->json(['message' => 'Ville introuvable.'], 404);

		return $this->json([
			'id' => $v->getId(),
			'nom' => $v->getNom(),
			'region' => $v->getRegion(),
		], 200);
	}

	#[Route('', name: 'create', methods: ['POST'])]
	public function create(Request $request): JsonResponse
	{
		$data = $this->decode($request);
		if ($data === null) return $this->json(['message' => 'JSON invalide.'], 400);

		$nom = trim((string)($data['nom'] ?? ''));
		if ($nom === '') return $this->json(['errors' => ['nom' => ['Champ obligatoire.']]], 422);
		if (mb_strlen($nom) > 20) return $this->json(['errors' => ['nom' => ['Max 20 caractères.']]], 422);

		$v = new VilleMaroc();
		$v->setNom($nom);
		$v->setRegion(isset($data['region']) ? (string)$data['region'] : null);

		$this->em->persist($v);
		$this->em->flush();

		return $this->json(['id' => $v->getId(), 'nom' => $v->getNom(), 'region' => $v->getRegion()], 201);
	}

	#[Route('/{id<\d+>}', name: 'update', methods: ['PUT'])]
	public function update(int $id, Request $request): JsonResponse
	{
		$v = $this->repo->find($id);
		if (!$v) return $this->json(['message' => 'Ville introuvable.'], 404);

		$data = $this->decode($request);
		if ($data === null) return $this->json(['message' => 'JSON invalide.'], 400);

		$nom = trim((string)($data['nom'] ?? ''));
		if ($nom === '') return $this->json(['errors' => ['nom' => ['Champ obligatoire.']]], 422);
		if (mb_strlen($nom) > 20) return $this->json(['errors' => ['nom' => ['Max 20 caractères.']]], 422);

		$v->setNom($nom);
		$v->setRegion(array_key_exists('region', $data) ? (string)$data['region'] : null);

		$this->em->flush();

		return $this->json(['id' => $v->getId(), 'nom' => $v->getNom(), 'region' => $v->getRegion()], 200);
	}

	#[Route('/{id<\d+>}', name: 'patch', methods: ['PATCH'])]
	public function patch(int $id, Request $request): JsonResponse
	{
		$v = $this->repo->find($id);
		if (!$v) return $this->json(['message' => 'Ville introuvable.'], 404);

		$data = $this->decode($request);
		if ($data === null) return $this->json(['message' => 'JSON invalide.'], 400);

		if (array_key_exists('nom', $data)) {
			$nom = trim((string)$data['nom']);
			if ($nom === '') return $this->json(['errors' => ['nom' => ['Champ obligatoire.']]], 422);
			if (mb_strlen($nom) > 20) return $this->json(['errors' => ['nom' => ['Max 20 caractères.']]], 422);
			$v->setNom($nom);
		}

		if (array_key_exists('region', $data)) {
			$v->setRegion($data['region'] === null ? null : (string)$data['region']);
		}

		$this->em->flush();

		return $this->json(['id' => $v->getId(), 'nom' => $v->getNom(), 'region' => $v->getRegion()], 200);
	}

	#[Route('/{id<\d+>}', name: 'delete', methods: ['DELETE'])]
	public function delete(int $id): JsonResponse
	{
		$v = $this->repo->find($id);
		if (!$v) return $this->json(['message' => 'Ville introuvable.'], 404);

		$this->em->remove($v);
		$this->em->flush();

		return $this->json(['message' => 'Ville supprimée.'], 200);
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
