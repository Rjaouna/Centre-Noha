<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class SymptomePageController extends AbstractController
{
	#[Route('/symptomes', name: 'symptome_index')]
	public function index()
	{
		return $this->render('symptome/index.html.twig');
	}
}
