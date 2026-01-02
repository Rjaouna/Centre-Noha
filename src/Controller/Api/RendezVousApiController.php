<?php

namespace App\Controller\Api;

use App\Entity\RendezVous;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/rendezvous')]
class RendezVousApiController extends AbstractController
{
	#[Route('/{id}/valider', name: 'api_rendezvous_valider', methods: ['POST'])]
	public function valider(RendezVous $rdv, EntityManagerInterface $em): JsonResponse
	{
		if ($rdv->getPraticien() !== $this->getUser()) {
			return $this->json(['error' => 'Accès refusé'], 403);
		}

		$rdv->setStatut('valide');
		$em->flush();

		return $this->json(['success' => true]);
	}

	#[Route('/{id}/annuler', name: 'api_rendezvous_annuler', methods: ['POST'])]
	public function annuler(RendezVous $rdv, EntityManagerInterface $em): JsonResponse
	{
		if ($rdv->getPraticien() !== $this->getUser()) {
			return $this->json(['error' => 'Accès refusé'], 403);
		}

		$rdv->setStatut('annule');
		$em->flush();

		return $this->json(['success' => true]);
	}
}
