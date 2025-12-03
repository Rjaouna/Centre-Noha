<?php

namespace App\Controller;

use App\Entity\FicheClient;
use App\Entity\RendezVous;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rdv', name: 'app_rdv_')]
class RendezVousController extends AbstractController
{
	#[Route('/', name: 'index', methods: ['GET'])]
	public function calendar(RendezVousRepository $repo): Response
	{
		$rdvs = $repo->findBy([], ['dateRdvAt' => 'ASC']);

		// Grouper par date (Y-m-d) si tu en as besoin ailleurs
		$rdvsByDay = [];
		foreach ($rdvs as $rdv) {
			if (!$rdv->getDateRdvAt()) {
				continue;
			}
			$dayKey = $rdv->getDateRdvAt()->format('Y-m-d');
			$rdvsByDay[$dayKey][] = $rdv;
		}

		// 🧮 Compteurs par jour de semaine pour la semaine en cours
		$countsByWeekday = [
			'Mon' => 0,
			'Tue' => 0,
			'Wed' => 0,
			'Thu' => 0,
			'Fri' => 0,
			'Sat' => 0,
			'Sun' => 0,
		];

		$startOfWeek = (new \DateTimeImmutable('monday this week'))->setTime(0, 0);
		$endOfWeek   = $startOfWeek->modify('+7 days');

		foreach ($rdvs as $rdv) {
			$date = $rdv->getDateRdvAt();
			if (!$date) {
				continue;
			}

			// Ne compter que la semaine en cours
			if ($date < $startOfWeek || $date >= $endOfWeek) {
				continue;
			}

			$weekday = $date->format('D'); // Mon, Tue, ...
			if (isset($countsByWeekday[$weekday])) {
				$countsByWeekday[$weekday]++;
			}
		}

		return $this->render('rdv/index.html.twig', [
			'rdvsByDay'      => $rdvsByDay,
			'rdvCounts'      => $countsByWeekday,
			'startOfWeek'    => $startOfWeek,
			'endOfWeek'      => $endOfWeek,
		]);
	}

	#[Route('/fiche-client/{id}/add', name: 'add', methods: ['POST'])]
	public function add(FicheClient $client, Request $request, EntityManagerInterface $em): Response
	{
		$rdv = new RendezVous();
		$rdv->setClient($client);
		$rdv->setDateRdvAt(new \DateTimeImmutable($request->get('dateRdvAt')));
		$rdv->setMotif($request->get('motif'));
		$rdv->setCommentaire($request->get('commentaire'));
		$rdv->setStatut($request->get('statut', 'A venir'));

		$em->persist($rdv);
		$em->flush();

		$this->addFlash('success', 'Rendez-vous ajouté avec succès.');

		return $this->redirectToRoute('app_fiche_client_show', [
			'id' => $client->getId(),
		]);
	}
}
