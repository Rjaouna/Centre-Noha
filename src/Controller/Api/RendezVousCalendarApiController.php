<?php
// src/Controller/Api/RendezVousCalendarApiController.php
namespace App\Controller\Api;

use App\Repository\RendezVousRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/rendezvous')]
class RendezVousCalendarApiController extends AbstractController
{
	#[Route('/events', name: 'api_rendezvous_events', methods: ['GET'])]
	public function events(Request $request, RendezVousRepository $repo): JsonResponse
	{
		// FullCalendar envoie start/end (ISO)
		$start = $request->query->get('start');
		$end   = $request->query->get('end');

		// Si tu as une méthode repo dédiée c'est mieux, sinon on fait simple:
		// On filtre ensuite en PHP si besoin
		$rdvs = $repo->findBy(['praticien' => $this->getUser()]);

		$events = [];
		foreach ($rdvs as $rdv) {
			$date = $rdv->getDate(); // DateTime (date)
			$h1 = $rdv->getHeureDebut(); // DateTime (time)
			$h2 = $rdv->getHeureFin();   // DateTime (time)

			// Construit des DateTime complets
			$startDt = (clone $date)->setTime(
				(int) $h1->format('H'),
				(int) $h1->format('i')
			);

			$endDt = (clone $date)->setTime(
				(int) $h2->format('H'),
				(int) $h2->format('i')
			);

			$events[] = [
				'id'    => $rdv->getId(),
				'title' => $rdv->getPatient()->getNom() . ' ' . $rdv->getPatient()->getNom(),
				'start' => $startDt->format(\DateTimeInterface::ATOM),
				'end'   => $endDt->format(\DateTimeInterface::ATOM),
				'extendedProps' => [
					'statut' => $rdv->getStatut(),
					'tel'    => $rdv->getPatient()->getTelephone(),
				]
			];
		}

		return $this->json($events);
	}
}
