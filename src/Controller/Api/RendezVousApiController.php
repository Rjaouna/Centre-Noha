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
		// 🔐 Sécurité
		if ($rdv->getPraticien() !== $this->getUser()) {
			return $this->json(['error' => 'Accès refusé'], 403);
		}

		// ⚠️ On ne valide QUE les rdv confirmés
		if ($rdv->getStatut() !== 'confirme') {
			return $this->json(['error' => 'Statut invalide'], 400);
		}

		// ⏱️ Calcul de la vraie date/heure de fin du RDV
		$date = $rdv->getDate();           // Date (Y-m-d)
		$heureFin = $rdv->getHeureFin();   // Time (H:i)

		$finRdv = (clone $date)->setTime(
			(int) $heureFin->format('H'),
			(int) $heureFin->format('i'),
			0
		);

		$now = new \DateTimeImmutable();

		// 🧠 LOGIQUE MÉTIER
		if ($finRdv <= $now) {
			// ❌ RDV raté (passé)
			$rdv->setStatut('rate');
			$rdv->setHeureDebut(new \DateTime('00:00'));
			$rdv->setHeureFin(new \DateTime('00:00'));
		} else {
			// ✅ RDV traité correctement
			$rdv->setStatut('valide');
			$rdv->setHeureDebut(new \DateTime('00:00'));
			$rdv->setHeureFin(new \DateTime('00:00'));
		}

		$em->flush();

		return $this->json([
			'success' => true,
			'newStatut' => $rdv->getStatut()
		]);
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
