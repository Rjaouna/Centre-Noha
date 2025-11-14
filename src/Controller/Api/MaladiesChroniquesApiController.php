<?php

namespace App\Controller\Api;

use App\Entity\FicheClient;
use App\Entity\MaladiesChroniques;
use App\Repository\MaladiesChroniquesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/maladies-chroniques')]
class MaladiesChroniquesApiController extends AbstractController
{
	#[Route('/{idClient}', name: 'api_maladies_show', methods: ['GET'])]
	public function show(
		int $idClient,
		MaladiesChroniquesRepository $repo
	): JsonResponse {
		$maladies = $repo->findOneBy(['client' => $idClient]);

		if (!$maladies) {
			return new JsonResponse([
				'exists' => false,
				'message' => 'Aucune maladie chronique enregistrée.'
			]);
		}

		return new JsonResponse([
			'exists' => true,
			'data'   => [
				'diabetique'     => $maladies->getDiabetique(),
				'hypertendu'     => $maladies->getHypertendu(),
				'cholesterol'    => $maladies->getCholesterol(),
				'allergieNasale' => $maladies->getAllergieNasale(),
				'autreMaladie'   => $maladies->getAutreMaladie(),
			]
		]);
	}

	#[Route('/add/{id}', name: 'api_maladies_add', methods: ['POST'])]
	public function add(
		FicheClient $client,
		Request $request,
		EntityManagerInterface $em
	): JsonResponse {

		$data = json_decode($request->getContent(), true);

		$maladies = new MaladiesChroniques();
		$maladies->setClient($client);
		$maladies->setDiabetique($data['diabetique'] ?? null);
		$maladies->setHypertendu($data['hypertendu'] ?? null);
		$maladies->setCholesterol($data['cholesterol'] ?? null);
		$maladies->setAllergieNasale($data['allergieNasale'] ?? null);
		$maladies->setAutreMaladie($data['autreMaladie'] ?? null);

		$em->persist($maladies);
		$em->flush();

		return new JsonResponse(['success' => true]);
	}

	#[Route('/edit/{id}', name: 'api_maladies_edit', methods: ['POST'])]
	public function edit(
		FicheClient $client,
		Request $request,
		EntityManagerInterface $em,
		MaladiesChroniquesRepository $repo
	): JsonResponse {

		$maladies = $repo->findOneBy(['client' => $client]);

		if (!$maladies) {
			$maladies = new MaladiesChroniques();
			$maladies->setClient($client);
			$em->persist($maladies);
		}

		$data = json_decode($request->getContent(), true);

		$maladies->setDiabetique($data['diabetique'] ?? null);
		$maladies->setHypertendu($data['hypertendu'] ?? null);
		$maladies->setCholesterol($data['cholesterol'] ?? null);
		$maladies->setAllergieNasale($data['allergieNasale'] ?? null);
		$maladies->setAutreMaladie($data['autreMaladie'] ?? null);

		$em->flush();

		return new JsonResponse(['success' => true]);
	}
}
