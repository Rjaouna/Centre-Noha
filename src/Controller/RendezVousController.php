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
	public function index(RendezVousRepository $repo): Response
	{
		$rdvs = $repo->findBy([], ['dateRdvAt' => 'ASC']);

		$grouped = [];
		foreach ($rdvs as $rdv) {
			if (!$rdv->getDateRdvAt()) continue;
			$day = $rdv->getDateRdvAt()->format('Y-m-d');
			$grouped[$day][] = $rdv;
		}

		return $this->render('rdv/index.html.twig', [
			'rdvsByDay' => $grouped,
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
