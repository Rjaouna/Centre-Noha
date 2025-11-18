<?php

namespace App\Controller;

use App\Entity\Paiement;
use App\Entity\FicheClient;
use App\Form\Paiement1Type;
use App\Repository\PaiementRepository;
use App\Repository\FicheClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/paiement')]
final class PaiementController extends AbstractController
{
    #[Route('/suivi-paiement/{id}', name: 'app_paiement_index', methods: ['GET'])]
    public function index(
        PaiementRepository $paiementRepository,
        FicheClient $patient
    ): Response {
        return $this->render('paiement/index.html.twig', [
            'paiements' => $paiementRepository->findBy(['client' => $patient]),
            'patient' => $patient
        ]);
    }


    #[Route('/new/{id}', name: 'app_paiement_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        int $id,
        FicheClientRepository $ficheRepository
    ): Response {
        $paiement = new Paiement();
        $patient = $ficheRepository->find($id);

        $form = $this->createForm(Paiement1Type::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $paiement->setClient($patient);

            $entityManager->persist($paiement);
            $entityManager->flush();

            return $this->redirectToRoute(
                'app_paiement_index',
                ['id' => $patient->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('paiement/new.html.twig', [
            'paiement' => $paiement,
            'patient' => $patient,
            'form' => $form,
        ]);
    }


    #[Route('/show/{id}', name: 'app_paiement_show', methods: ['GET'])]
    public function show(Paiement $paiement): Response
    {
        return $this->render('paiement/show.html.twig', [
            'paiement' => $paiement,
        ]);
    }


    #[Route('/edit/{id}', name: 'app_paiement_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Paiement $paiement,
        EntityManagerInterface $entityManager
    ): Response {

        $form = $this->createForm(Paiement1Type::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute(
                'app_paiement_index',
                ['id' => $paiement->getClient()->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('paiement/edit.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }


    #[Route('/delete/{id}', name: 'app_paiement_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Paiement $paiement,
        EntityManagerInterface $entityManager
    ): Response {

        $clientId = $paiement->getClient()->getId();

        if ($this->isCsrfTokenValid('delete' . $paiement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($paiement);
            $entityManager->flush();
        }

        return $this->redirectToRoute(
            'app_paiement_index',
            ['id' => $clientId],
            Response::HTTP_SEE_OTHER
        );
    }
}
