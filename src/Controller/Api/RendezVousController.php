<?php

namespace App\Controller\Api;

use App\Entity\RendezVous;
use App\Entity\FicheClient;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RendezVousController extends AbstractController
{
	#[Route('/rdv', name: 'app_rdv_index', methods: ['GET'])]
	public function index(RendezVousRepository $repository): Response
	{
		$rdvs = $repository->findBy([], ['dateRdvAt' => 'ASC']);

		return $this->render('rdv/index.html.twig', [
			'rdvs' => $rdvs,
		]);
	}
	#[Route('/fiche-client/{id}/rdv/add', name: 'app_rdv_add', methods: ['POST'])]
	public function add(
		FicheClient $client,
		Request $request,
		EntityManagerInterface $em
	): Response {

		$rdv = new RendezVous();
		$rdv->setClient($client);

		$rdv->setDateRdvAt(
			new \DateTimeImmutable($request->get('dateRdvAt'))
		);

		$rdv->setMotif($request->get('motif'));
		$rdv->setCommentaire($request->get('commentaire'));
		$rdv->setStatut($request->get('statut', 'À venir'));

		$em->persist($rdv);
		$em->flush();

		$this->addFlash('success', 'Rendez-vous ajouté avec succès.');

		return $this->redirectToRoute('app_fiche_client_show', [
			'id' => $client->getId()
		]);
	}
}
