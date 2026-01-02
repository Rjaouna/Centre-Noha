<?php

namespace App\Controller\Api;

use App\Repository\RendezVousRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/rendezvous')]
class RendezVousListApiController extends AbstractController
{
	#[Route('/next', name: 'api_rendezvous_next', methods: ['GET'])]
	public function next(RendezVousRepository $repo): JsonResponse
	{
		$rdvs = $repo->createQueryBuilder('r')
			->andWhere('r.praticien = :user')
			->andWhere('r.statut != :annule')
			->andWhere('r.date >= :today')
			->setParameter('user', $this->getUser())
			->setParameter('annule', 'annule')
			->setParameter('today', new \DateTime('today'))
			->orderBy('r.date', 'ASC')
			->addOrderBy('r.heureDebut', 'ASC')
			->setMaxResults(4)
			->getQuery()
			->getResult();

		$data = [];

		foreach ($rdvs as $rdv) {
			$data[] = [
				'id'      => $rdv->getId(),
				'nom'     => $rdv->getPatient()->getNom(),
				'date'    => $rdv->getDate()->format('d/m/Y'),
				'heure'   => $rdv->getHeureDebut()->format('H:i'),
				'statut'  => $rdv->getStatut(),
			];
		}

		return $this->json($data);
	}
}
