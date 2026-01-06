<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/auth', name: 'api_auth_')]
class AuthPingController extends AbstractController
{
	#[Route('/ping', name: 'ping', methods: ['GET'])]
	public function ping(RequestStack $requestStack): JsonResponse
	{
		/** @var \App\Entity\User|null $user */
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

		// fallback si nom/prenom vide
		if ($fullName === '') {
			$fullName = $user->getUserIdentifier(); // email
		}

		// (optionnel) cabinet si tu l'ajoutes plus tard
		$cabinetPayload = null;
		// $cabinet = $user->getCabinet(); ...

		return new JsonResponse([
			'ok' => true,
			'user' => [
				'prenom' => $prenom ?: null,
				'nom' => $nom ?: null,
				'fullName' => $fullName,
				'email' => $user->getEmail(),
			],
			'cabinet' => $cabinetPayload,
		]);
	}
}
