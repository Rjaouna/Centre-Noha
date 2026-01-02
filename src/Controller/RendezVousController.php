<?php
// src/Controller/RendezVousController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RendezVousController extends AbstractController
{
	#[Route('/rendezvous/calendrier', name: 'rendezvous_calendrier')]
	public function calendrier(): Response
	{
		return $this->render('rendezvous/calendrier.html.twig');
	}
}
