<?php

namespace App\Controller\Api;

use App\Entity\RadioType;
use App\Repository\RadioTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/radio-types')]
class RadioTypeApiController extends AbstractController
{
	#[Route('', name: 'api_radio_type_list', methods: ['GET'])]
	public function list(RadioTypeRepository $repo): JsonResponse
	{
		return $this->json(
			array_map(fn(RadioType $r) => [
				'id' => $r->getId(),
				'nom' => $r->getNom(),
				'description' => $r->getDescription(),
				'zone_corps' => $r->getZoneCorps(),
			], $repo->findBy([], ['nom' => 'ASC']))
		);
	}

	#[Route('/{id}', name: 'api_radio_type_get', methods: ['GET'])]
	public function get(RadioType $radioType): JsonResponse
	{
		return $this->json([
			'id' => $radioType->getId(),
			'nom' => $radioType->getNom(),
			'description' => $radioType->getDescription(),
			'zone_corps' => $radioType->getZoneCorps(),
		]);
	}

	#[Route('', name: 'api_radio_type_create', methods: ['POST'])]
	public function create(Request $request, EntityManagerInterface $em): JsonResponse
	{
		$radioType = new RadioType();

		$error = $this->hydrate($radioType, $request);
		if ($error) {
			return $this->json(
				['success' => false, 'message' => $error],
				400
			);
		}

		$em->persist($radioType);
		$em->flush();

		return $this->json([
			'success' => true,
			'id' => $radioType->getId()
		]);
	}

	#[Route('/{id}', name: 'api_radio_type_update', methods: ['PUT'])]
	public function update(
		RadioType $radioType,
		Request $request,
		EntityManagerInterface $em
	): JsonResponse {
		$error = $this->hydrate($radioType, $request);
		if ($error) {
			return $this->json(
				['success' => false, 'message' => $error],
				400
			);
		}

		$em->flush();

		return $this->json(['success' => true]);
	}

	#[Route('/{id}', name: 'api_radio_type_delete', methods: ['DELETE'])]
	public function delete(
		RadioType $radioType,
		EntityManagerInterface $em
	): JsonResponse {
		$em->remove($radioType);
		$em->flush();

		return $this->json(['success' => true]);
	}

	/* ==========================
       VALIDATION + HYDRATATION
    ========================== */
	private function hydrate(RadioType $r, Request $request): ?string
	{
		$data = json_decode($request->getContent(), true);

		if (
			empty($data['nom']) ||
			empty($data['description']) ||
			empty($data['zone_corps'])
		) {
			return 'Tous les champs sont obligatoires.';
		}

		$r->setNom(trim($data['nom']));
		$r->setDescription(trim($data['description']));
		$r->setZoneCorps(trim($data['zone_corps']));

		return null;
	}
}
