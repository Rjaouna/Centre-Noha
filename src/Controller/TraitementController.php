<?php
// src/Controller/TraitementController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/traitements', name: 'traitement_')]
class TraitementController extends AbstractController
{
	#[Route('', name: 'index')]
	public function index(): Response
	{
		return $this->render('traitement/index.html.twig');
	}
}
