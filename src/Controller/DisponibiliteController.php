<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DisponibiliteController extends AbstractController
{
	#[Route('/disponibilites', name: 'disponibilite_index')]
	public function index(): Response
	{
		return $this->render('disponibilite/index.html.twig');
	}
}
