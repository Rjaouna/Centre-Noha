<?php

namespace App\Controller;

use App\Entity\FicheClient;
use App\Form\FicheClientType;
use App\Repository\RendezVousRepository;
use App\Repository\FicheClientRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(FicheClientRepository $repo, RendezVousRepository $rdvRepo): Response
    {
        // 👉 ON CRÉE LE FORMULAIRE
        $rdvs = $rdvRepo->findBy(['statut' => 'A venir'], ['dateRdvAt' => 'ASC']);
        $fiche = new FicheClient();
        $form = $this->createForm(FicheClientType::class, $fiche);

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),   // 🔥 IMPORTANT
            'totalClients' => $repo->count([]),
            'totalRdv'     => $rdvRepo->count(['statut' => 'A venir']),
            'rdvs' => $rdvs
        ]);
    }
}
