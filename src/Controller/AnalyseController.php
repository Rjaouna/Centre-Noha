<?php

namespace App\Controller;

use App\Entity\Analyse;
use App\Repository\AnalyseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/analyse')]
class AnalyseController extends AbstractController
{
	#[Route('/', name: 'analyse_index', methods: ['GET'])]
	public function index(AnalyseRepository $repo)
	{
		return $this->render('analyse/index.html.twig', [
			'analyses' => $repo->findAll()
		]);
	}

	#[Route('/save', name: 'analyse_save', methods: ['POST'])]
	public function save(Request $request, EntityManagerInterface $em): JsonResponse
	{
		// CSRF
		if (!$this->isCsrfTokenValid('analyse_crud', (string) $request->request->get('_token'))) {
			return $this->json(['success' => false, 'message' => 'Token CSRF invalide'], 419);
		}

		$id = $request->request->get('id');
		$name = trim((string) $request->request->get('name'));
		$description = $request->request->get('description');

		if ($name === '') {
			return $this->json(['success' => false, 'message' => 'Le nom est obligatoire'], 422);
		}

		$analyse = $id ? $em->getRepository(Analyse::class)->find($id) : new Analyse();
		if (!$analyse) {
			return $this->json(['success' => false, 'message' => 'Analyse introuvable'], 404);
		}

		$analyse->setName($name);
		$analyse->setDescription($description);

		$em->persist($analyse);
		$em->flush();

		return $this->json([
			'success' => true,
			'analyse' => [
				'id' => $analyse->getId(),
				'name' => $analyse->getName(),
				'description' => $analyse->getDescription() ?? '-',
			]
		]);
	}

	#[Route('/get/{id}', name: 'analyse_get', methods: ['GET'])]
	public function getAnalyse(Analyse $analyse): JsonResponse
	{
		return $this->json([
			'id' => $analyse->getId(),
			'name' => $analyse->getName(),
			'description' => $analyse->getDescription(),
		]);
	}

	#[Route('/delete/{id}', name: 'analyse_delete', methods: ['POST'])]
	public function delete(Request $request, Analyse $analyse, EntityManagerInterface $em): JsonResponse
	{
		if (!$this->isCsrfTokenValid('analyse_delete' . $analyse->getId(), (string) $request->request->get('_token'))) {
			return $this->json(['success' => false, 'message' => 'Token CSRF invalide'], 419);
		}

		$em->remove($analyse);
		$em->flush();

		return $this->json(['success' => true]);
	}
}
