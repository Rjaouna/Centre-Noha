<?php

namespace App\Controller;

use App\Entity\Cabinet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CabinetController extends AbstractController
{
	#[Route('/cabinet', name: 'app_cabinet_index')]
	public function index(EntityManagerInterface $em)
	{
		$cabinet = $em->getRepository(Cabinet::class)->findOneBy([]) ?? new Cabinet();

		return $this->render('cabinet/index.html.twig', [
			'cabinet' => $cabinet,
		]);
	}

	#[Route('/cabinet/save', name: 'app_cabinet_save', methods: ['POST'])]
	public function save(Request $request, EntityManagerInterface $em): JsonResponse
	{
		try {
			$data = json_decode($request->getContent(), true);

			$cabinet = $em->getRepository(Cabinet::class)->findOneBy([]) ?? new Cabinet();

			$cabinet->setNom($data['nom'] ?? '');
			$cabinet->setType($data['type'] ?? '');
			$cabinet->setAdresse($data['adresse'] ?? '');
			$cabinet->setVille($data['ville'] ?? null);
			$cabinet->setTelephone($data['telephone'] ?? '');
			$cabinet->setEmail($data['email'] ?? '');

			$em->persist($cabinet);
			$em->flush();

			return new JsonResponse(['success' => true, 'message' => "Cabinet sauvegardé avec succès"]);
		} catch (\Exception $e) {
			return new JsonResponse(['success' => false, 'error' => $e->getMessage()], 400);
		}
	}
}
