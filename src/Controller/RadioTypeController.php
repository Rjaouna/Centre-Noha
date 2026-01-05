<?php

namespace App\Controller;

use App\Repository\RadioTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/radio-types')]
class RadioTypeController extends AbstractController
{
	#[Route('/', name: 'radio_type_index', methods: ['GET'])]
	public function index(RadioTypeRepository $repo): Response
	{
		return $this->render('radio_type/index.html.twig', [
			'radioTypes' => $repo->findBy([], ['nom' => 'ASC'])
		]);
	}
}
