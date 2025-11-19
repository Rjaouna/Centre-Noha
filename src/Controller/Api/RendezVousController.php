<?php

namespace App\Controller\Api;

use App\Entity\RendezVous;
use App\Entity\FicheClient;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RendezVousController extends AbstractController
{
	#[Route('/rdv', name: 'app_rdv_index', methods: ['GET'])]
	public function index(RendezVousRepository $repository): Response
	{
		$rdvs = $repository->findBy(['statut' => 'A venir'], ['dateRdvAt' => 'ASC']);

		return $this->render('rdv/index.html.twig', [
			'rdvs' => $rdvs,
		]);
	}
	#[Route('/api/rdv/validate/{id}', name: 'api_rdv_validate', methods: ['POST'])]
	public function validateRdv(RendezVous $rdv, EntityManagerInterface $em): JsonResponse
	{
		// Mettre le statut à "Passé"
		$rdv->setStatut('Passé');

		$em->persist($rdv);
		$em->flush();

		return new JsonResponse([
			'success' => true,
			'message' => 'Rendez-vous validé',
			'newStatus' => $rdv->getStatut()
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
