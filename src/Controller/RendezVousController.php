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
	/**
	 * 📅 PAGE PLANNING (FullCalendar)
	 */
	#[Route('/liste', name: 'index', methods: ['GET'])]
	public function index(): Response
	{
		return $this->render('rdv/index.html.twig');
	}

	/**
	 * 📡 API FullCalendar
	 * Retourne les RDV entre start / end
	 */
	#[Route('/api/calendar', name: 'api_calendar', methods: ['GET'])]
	public function calendarApi(
		Request $request,
		RendezVousRepository $repo
	): Response {
		$start = new \DateTimeImmutable($request->query->get('start'));
		$end   = new \DateTimeImmutable($request->query->get('end'));

		$rdvs = $repo->createQueryBuilder('r')
			->where('r.dateRdvAt BETWEEN :start AND :end')
			->setParameter('start', $start)
			->setParameter('end', $end)
			->orderBy('r.dateRdvAt', 'ASC')
			->getQuery()
			->getResult();

		$events = [];

		foreach ($rdvs as $rdv) {
			$events[] = [
				'id'    => $rdv->getId(),
				'title' => $rdv->getClient()->getNom(),
				'start' => $rdv->getDateRdvAt()->format('Y-m-d H:i'),
				'color' => match ($rdv->getStatut()) {
					'A venir' => '#0d6efd',
					'Annulé'  => '#dc3545',
					'Terminé' => '#198754',
					default   => '#6c757d',
				},
				'extendedProps' => [
					'clientId'   => $rdv->getClient()->getId(),
					'telephone'  => $rdv->getClient()->getTelephone(),
					'motif'      => $rdv->getMotif(),
					'statut'     => $rdv->getStatut(),
				],
			];
		}

		return $this->json($events);
	}

	/**
	 * ➕ AJOUT RDV (depuis fiche client)
	 */
	#[Route('/fiche-client/{id}/add', name: 'add', methods: ['POST'])]
	public function add(
		FicheClient $client,
		Request $request,
		EntityManagerInterface $em
	): Response {
		$rdv = new RendezVous();

		$rdv->setClient($client);
		$rdv->setDateRdvAt(new \DateTimeImmutable($request->request->get('dateRdvAt')));
		$rdv->setMotif($request->request->get('motif'));
		$rdv->setCommentaire($request->request->get('commentaire'));
		$rdv->setStatut($request->request->get('statut', 'A venir'));

		$em->persist($rdv);
		$em->flush();

		$this->addFlash('success', 'Rendez-vous ajouté avec succès.');

		return $this->redirectToRoute('app_fiche_client_show', [
			'id' => $client->getId(),
		]);
	}
}
