<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PrestationPriceController extends AbstractController
{
	#[Route('/prestation-price', name: 'prestation_price_index', methods: ['GET'])]
	public function index(): Response
	{
		return $this->render('prestation_price/index.html.twig');
	}
}
