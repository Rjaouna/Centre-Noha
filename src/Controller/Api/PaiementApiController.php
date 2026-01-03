<?php

namespace App\Controller\Api;

use App\Entity\FicheClient;
use App\Entity\Paiement;
use App\Repository\PaiementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/paiements')]
class PaiementApiController extends AbstractController
{
	#[Route('/client/{id}', name: 'api_paiements_by_client', methods: ['GET'])]
	public function listByClient(FicheClient $client, PaiementRepository $repo): JsonResponse
	{
		// sécurité basique : tu peux renforcer selon ton app (praticien, cabinet, etc.)
		// exemple si tu as un lien praticien->patients, ici à adapter si besoin.

		$paiements = $repo->findBy(['client' => $client], ['createdAt' => 'DESC']);

		$data = array_map(function (Paiement $p) {
			return [
				'id' => $p->getId(),
				'prixTotal' => (float) $p->getPrixTotal(),
				'montantPaye' => (float) $p->getMontantPaye(),
				'reste' => (float) $p->getReste(),
				'typePaiement' => $p->getTypePaiement(),
				'statut' => $p->getStatutCalc(),
				'date' => $p->getCreatedAt()?->format('d/m/Y H:i'),
			];
		}, $paiements);

		return $this->json($data);
	}

	#[Route('/add/{id}', name: 'api_paiements_add', methods: ['POST'])]
	public function add(FicheClient $client, Request $request, EntityManagerInterface $em): JsonResponse
	{
		$payload = json_decode($request->getContent(), true) ?? [];

		$total = (float) ($payload['prixTotal'] ?? 0);
		$paye  = (float) ($payload['montantPaye'] ?? 0);
		$type  = (string) ($payload['typePaiement'] ?? '');

		if ($total <= 0 || $paye < 0 || $paye > $total || $type === '') {
			return $this->json(['success' => false, 'message' => 'Données invalides'], 400);
		}

		$reste = $total - $paye;

		$paiement = (new Paiement())
			->setClient($client)
			->setPrixTotal(number_format($total, 2, '.', ''))
			->setMontantPaye(number_format($paye, 2, '.', ''))
			->setReste(number_format($reste, 2, '.', ''))
			->setTypePaiement($type);

		$em->persist($paiement);
		$em->flush();

		return $this->json(['success' => true]);
	}

	#[Route('/{id}/edit', name: 'api_paiements_edit', methods: ['PUT'])]
	public function edit(Paiement $paiement, Request $request, EntityManagerInterface $em): JsonResponse
	{
		$payload = json_decode($request->getContent(), true) ?? [];

		$total = (float) ($payload['prixTotal'] ?? 0);
		$paye  = (float) ($payload['montantPaye'] ?? 0);
		$type  = (string) ($payload['typePaiement'] ?? '');

		if ($total <= 0 || $paye < 0 || $paye > $total || $type === '') {
			return $this->json(['success' => false, 'message' => 'Données invalides'], 400);
		}

		$reste = $total - $paye;

		$paiement
			->setPrixTotal(number_format($total, 2, '.', ''))
			->setMontantPaye(number_format($paye, 2, '.', ''))
			->setReste(number_format($reste, 2, '.', ''))
			->setTypePaiement($type);

		$em->flush();

		return $this->json(['success' => true]);
	}

	#[Route('/{id}/regulariser', name: 'api_paiements_regulariser', methods: ['POST'])]
	public function regulariser(Paiement $paiement, EntityManagerInterface $em): JsonResponse
	{
		$total = (float) $paiement->getPrixTotal();
		$paiement->setMontantPaye(number_format($total, 2, '.', ''));
		$paiement->setReste('0.00');

		$em->flush();

		return $this->json(['success' => true]);
	}

	#[Route('/{id}', name: 'api_paiements_delete', methods: ['DELETE'])]
	public function delete(Paiement $paiement, EntityManagerInterface $em): JsonResponse
	{
		$em->remove($paiement);
		$em->flush();

		return $this->json(['success' => true]);
	}
}
