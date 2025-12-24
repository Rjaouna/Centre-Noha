<?php
namespace App\Controller;

use App\Entity\MaladieChronique;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\MaladieChroniqueRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/maladies-chroniques')]
class MaladieChroniqueApiController extends AbstractController
{
	#[Route('/', methods: ['GET'])]
	public function list(MaladieChroniqueRepository $repo): JsonResponse
	{
		return $this->json(array_map(fn($m) => [
			'id' => $m->getId(),
			'nom' => $m->getNom(),
			'description' => $m->getDescription()
		], $repo->findAll()));
	}

	#[Route('', methods: ['POST'])]
	public function create(Request $request, EntityManagerInterface $em): JsonResponse
	{
		$data = json_decode($request->getContent(), true);

		$m = new MaladieChronique();
		$m->setNom($data['nom']);
		$m->setDescription($data['description'] ?? null);

		$em->persist($m);
		$em->flush();

		return $this->json(['success' => true]);
	}

	#[Route('/{id}', methods: ['GET'])]
	public function show(MaladieChronique $m): JsonResponse
	{
		return $this->json([
			'id' => $m->getId(),
			'nom' => $m->getNom(),
			'description' => $m->getDescription()
		]);
	}

	#[Route('/{id}', methods: ['PUT'])]
	public function update(
		MaladieChronique $m,
		Request $request,
		EntityManagerInterface $em
	): JsonResponse {
		$data = json_decode($request->getContent(), true);
		$m->setNom($data['nom']);
		$m->setDescription($data['description'] ?? null);
		$em->flush();

		return $this->json(['success' => true]);
	}

	#[Route('/{id}', methods: ['DELETE'])]
	public function delete(
		MaladieChronique $m,
		EntityManagerInterface $em
	): JsonResponse {
		$em->remove($m);
		$em->flush();

		return $this->json(['success' => true]);
	}
}
