<?php

namespace App\Controller;

use App\Entity\FicheClient;
use App\Form\FicheClientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // 👉 ON CRÉE LE FORMULAIRE
        $fiche = new FicheClient();
        $form = $this->createForm(FicheClientType::class, $fiche);

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),   // 🔥 IMPORTANT
        ]);
    }
}
