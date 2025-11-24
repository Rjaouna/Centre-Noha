<?php

namespace App\Controller\Api;

use App\Repository\RendezVousRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetRendezVousController extends AbstractController
{
	#[Route('/api/rdv/today', name: 'api_rdv_today', methods: ['GET'])]
	public function rdvToday(RendezVousRepository $repo): Response
	{
		$today = new \DateTimeImmutable('today');
		$tomorrow = $today->modify('+1 day');

		$rdvs = $repo->createQueryBuilder('r')
			->where('r.dateRdvAt >= :today')
			->andWhere('r.dateRdvAt < :tomorrow')
			->andWhere('r.statut != :valide')
			->setParameter('valide', 'passé')
			->setParameter('today', $today)
			->setParameter('tomorrow', $tomorrow)
			->orderBy('r.dateRdvAt', 'ASC')
			->getQuery()
			->getResult();

		$data = [];

		foreach ($rdvs as $rdv) {
			$data[] = [
				'id' => $rdv->getId(),
				'nom' => $rdv->getClient()->getNom(),
				'motif' => $rdv->getMotif(),
				'statut' => $rdv->getStatut(),
				'date' => $rdv->getDateRdvAt()->format('d/m/Y H:i')
			];
		}

		return $this->json($data);
	}
}
