<?php

namespace App\Controller\Api;

use App\Entity\Medicine;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MedicineController extends AbstractController
{
	#[Route('/medicine/new', name: 'medicine_new', methods: ['GET'])]
	public function new(): Response
	{
		return $this->render('medicine/new.html.twig');
	}

	#[Route('/api/medicine', name: 'api_medicine_create', methods: ['POST'])]
	public function create(
		Request $request,
		EntityManagerInterface $em
	): JsonResponse {

		// 🔐 (optionnel mais recommandé)
		if (!$this->getUser()) {
			return $this->json(['error' => 'Non autorisé'], 401);
		}

		$data = json_decode($request->getContent(), true);

		if (!$data) {
			return $this->json(['error' => 'JSON invalide'], 400);
		}

		// ✅ Vérifications minimales
		if (
			empty($data['code']) ||
			empty($data['name']) ||
			empty($data['uniteDosage']) ||
			!isset($data['ppv'])
		) {
			return $this->json([
				'error' => 'Champs obligatoires manquants'
			], 400);
		}

		$medicine = new Medicine();
		$medicine
			->setCode($data['code'])
			->setName($data['name'])
			->setDci($data['dci'] ?? null)
			->setDosage($data['dosage'] ?? null)
			->setUniteDosage($data['uniteDosage'])
			->setForme($data['forme'] ?? null)
			->setPresentation($data['presentation'] ?? null)
			->setPpv((float) $data['ppv'])
			->setPh(isset($data['ph']) ? (float) $data['ph'] : null)
			->setIsGeneric($data['isGeneric'] ?? null)
			->setTauxRembourssement($data['tauxRembourssement'] ?? null);

		$em->persist($medicine);
		$em->flush();

		return $this->json([
			'success' => true,
			'medicine' => [
				'id' => $medicine->getId(),
				'name' => $medicine->getName(),
				'code' => $medicine->getCode()
			]
		], 201);
	}
}
