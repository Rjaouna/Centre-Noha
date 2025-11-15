<?php

namespace App\Controller\Api;

use App\Entity\Paiement;
use App\Form\PaiementType;
use App\Entity\FicheClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SetPaimentController extends AbstractController
{
    #[Route('/fiche/{id}/paiement/ajax', name: 'ajouter_paiement_ajax', methods: ['POST'])]
    public function ajouterPaiementAjax(
        Request $request,
        FicheClient $ficheClient,
        EntityManagerInterface $em
    ): Response {

        $paiement = new Paiement();
        $paiement->setClient($ficheClient);

        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json([
                'success' => false,
                'errors' => (string) $form->getErrors(true, false)
            ], 400);
        }

        $em->persist($paiement);
        $em->flush();

        return $this->json([
            'success' => true,
            'message' => 'Paiement ajouté',
            'paiement' => [
                'montant' => $paiement->getMontantPaye(),
                'reste' => $paiement->getReste(),
                'type' => $paiement->getTypePaiement(),
                'date' => $paiement->getCreatedAt()->format('d/m/Y H:i')
            ]
        ]);
    }

    #[Route('/paiement/{id}/edit', name: 'paiement_edit', methods: ['GET'])]
    public function edit(Paiement $paiement): Response
    {
        $form = $this->createForm(PaiementType::class, $paiement);

        return $this->render('paiement/_edit_form.html.twig', [
            'formPaiement' => $form->createView(),
            'paiement' => $paiement
        ]);
    }

    #[Route('/paiement/{id}/valider', name: 'paiement_valider', methods: ['POST'])]
    public function validerPaiement(
        Paiement $paiement,
        EntityManagerInterface $em
    ): JsonResponse {

        $paiement->setReste("0");

        // Mise à jour timestamp + utilisateur
        $paiement->setUpdatedAt(new \DateTimeImmutable());

        if ($this->getUser()) {
            $paiement->setUpdatedBy($this->getUser());
        }

        $em->flush();

        return $this->json([
            "success" => true,
            "message" => "Le paiement a été soldé avec succès."
        ]);
    }
}
