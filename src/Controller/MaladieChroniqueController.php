<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MaladieChroniqueController extends AbstractController
{
	#[Route('/maladies/chroniques', name: 'maladie_chronique_index')]
	public function index(): Response
	{
		return $this->render('maladie_chronique/index.html.twig');
	}
}
