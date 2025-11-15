<?php

namespace App\Controller;

use App\Entity\SuiviSoin;
use App\Form\SuiviSoinType;
use App\Repository\SuiviSoinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/suivi/soin')]
final class SuiviSoinController extends AbstractController
{
    #[Route(name: 'app_suivi_soin_index', methods: ['GET'])]
    public function index(SuiviSoinRepository $suiviSoinRepository): Response
    {
        return $this->render('suivi_soin/index.html.twig', [
            'suivi_soins' => $suiviSoinRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_suivi_soin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $suiviSoin = new SuiviSoin();
        $form = $this->createForm(SuiviSoinType::class, $suiviSoin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($suiviSoin);
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_soin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('suivi_soin/new.html.twig', [
            'suivi_soin' => $suiviSoin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_suivi_soin_show', methods: ['GET'])]
    public function show(SuiviSoin $suiviSoin): Response
    {
        return $this->render('suivi_soin/show.html.twig', [
            'suivi_soin' => $suiviSoin,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_suivi_soin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SuiviSoin $suiviSoin, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SuiviSoinType::class, $suiviSoin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_soin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('suivi_soin/edit.html.twig', [
            'suivi_soin' => $suiviSoin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_suivi_soin_delete', methods: ['POST'])]
    public function delete(Request $request, SuiviSoin $suiviSoin, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$suiviSoin->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($suiviSoin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_suivi_soin_index', [], Response::HTTP_SEE_OTHER);
    }
}
