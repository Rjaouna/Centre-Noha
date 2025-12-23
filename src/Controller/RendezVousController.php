<?php

namespace App\Controller;

use App\Repository\RendezVousRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rdv', name: 'app_rdv_')]
class RendezVousController extends AbstractController
{
	/**
	 * 📅 Page planning (FullCalendar)
	 */
	#[Route('/', name: 'index', methods: ['GET'])]
	public function index(): Response
	{
		return $this->render('rdv/index.html.twig');
	}

	/**
	 * 📡 API FullCalendar
	 */
	#[Route('/api/calendar', name: 'api_calendar', methods: ['GET'])]
	public function calendarApi(RendezVousRepository $repo): Response
	{
		$rdvs = $repo->findAll();

		$events = [];

		foreach ($rdvs as $rdv) {
			if (!$rdv->getDateRdvAt()) {
				continue;
			}

			$events[] = [
				'id'    => $rdv->getId(),
				'title' => $rdv->getClient()->getNom(),
				'start' => $rdv->getDateRdvAt()->format(\DateTimeInterface::ATOM), // ISO 8601
				'color' => match ($rdv->getStatut()) {
					'A venir' => '#0d6efd',
					'Annulé'  => '#dc3545',
					'Terminé' => '#198754',
					default   => '#6c757d',
				},
				'extendedProps' => [
					'clientId' => $rdv->getClient()->getId(),
					'statut'   => $rdv->getStatut(),
					'motif'    => $rdv->getMotif(),
				],
			];
		}

		return $this->json($events);
	}
}
