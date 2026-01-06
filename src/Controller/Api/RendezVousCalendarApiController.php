<?php
// src/Controller/Api/RendezVousCalendarApiController.php
namespace App\Controller\Api;

use App\Entity\RendezVous;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
				'title' => $rdv->getPatient()->getNom() . ' ' . $rdv->getPatient()->getPrenom(),
				'start' => $startDt->format(\DateTimeInterface::ATOM),
				'end'   => $endDt->format(\DateTimeInterface::ATOM),
				'extendedProps' => [
					'statut'    => $rdv->getStatut(),
					'tel'       => $rdv->getPatient()->getTelephone(),
					'expiresAt' => $rdv->getExpiresAt()->format(\DateTimeInterface::ATOM),
				]
			];
		}

		return $this->json($events);
	}

	#[Route('/list', name: 'api_rendezvous_list', methods: ['GET'])]
	public function list(RendezVousRepository $repo): JsonResponse
	{
		$rdvs = $repo->createQueryBuilder('r')
			->andWhere('r.praticien = :p')
			->andWhere('r.statut = :s')
			->setParameter('p', $this->getUser())
			->setParameter('s', 'confirme')
			->orderBy('r.date', 'ASC')
			->addOrderBy('r.heureDebut', 'ASC')
			->getQuery()
			->getResult();

		$now = new \DateTimeImmutable();
		$data = [];

		foreach ($rdvs as $rdv) {
			$date = $rdv->getDate(); // DateTime (date)
			$hFin = $rdv->getHeureFin(); // DateTime (time)

			// ✅ On construit la vraie date/heure de FIN du rdv
			$endDt = (clone $date)->setTime(
				(int) $hFin->format('H'),
				(int) $hFin->format('i'),
				0
			);

			$data[] = [
				'id'        => $rdv->getId(),
				'nom'       => $rdv->getPatient()->getNom(),
				'date'      => $rdv->getDate()->format('d/m/Y'),
				'heureDebut' => $rdv->getHeureDebut()->format('H:i'),
				'heureFin'  => $rdv->getHeureFin()->format('H:i'),
				'isLate'    => $endDt <= $now, // ✅ LA VRAIE CONDITION
			];
		}
		return $this->json($data);
	}


	#[Route('/{id}/absent', name: 'api_rendezvous_absent', methods: ['POST'])]
	public function absent(RendezVous $rdv, EntityManagerInterface $em): JsonResponse
	{
		if ($rdv->getPraticien() !== $this->getUser()) {
			return $this->json(['error' => 'Accès refusé'], 403);
		}

		// on autorise "absent" seulement si c'est confirmé
		if ($rdv->getStatut() !== 'confirme') {
			return $this->json(['error' => 'Statut invalide'], 400);
		}

		$rdv->setStatut('absent');
		$em->flush();

		return $this->json(['success' => true]);
	}
}
