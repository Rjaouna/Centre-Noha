<?php

namespace App\Controller\Api;

use App\Controller\CabinetController;
use App\Repository\CabinetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/auth', name: 'api_auth_')]
class AuthPingController extends AbstractController
{
	#[Route('/ping', name: 'ping', methods: ['GET'])]
	public function ping(
		RequestStack $requestStack,
		CabinetRepository $cabinetRepository
	): JsonResponse {
		$user = $this->getUser();

		if (!$user) {
			return new JsonResponse([
				'ok' => false,
				'reason' => 'unauthenticated',
			], 401);
		}

		$nom = $user->getNom() ?? '';
		$prenom = $user->getPrenom() ?? '';
		$fullName = trim($prenom . ' ' . $nom);

		if ($fullName === '') {
			$fullName = $user->getUserIdentifier();
		}

		$cabinet = $cabinetRepository->findOneBy([]);

		$cabinetData = null;
		if ($cabinet) {
			$cabinetData = [
				'id'        => $cabinet->getId(),
				'nom'       => $cabinet->getNom(),
				'type'      => $cabinet->getType(),
				'adresse'   => $cabinet->getAdresse(),
				'ville'     => $cabinet->getVille(),
				'telephone' => $cabinet->getTelephone(),
				'email'     => $cabinet->getEmail(),
			];
		}

		return new JsonResponse([
			'ok' => true,
			'user' => [
				'prenom' => $prenom ?: null,
				'nom' => $nom ?: null,
				'fullName' => $fullName,
				'email' => $user->getEmail(),
			],
			'cabinet' => $cabinetData,
		]);
	}
}
