<?php

namespace App\Controller;

use App\Entity\Cabinet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GetNameCabinet extends AbstractController
{
	#[Route('/get/cabinet/name', name: 'app_nom_cabinet')]
	public function index(EntityManagerInterface $em)
	{
		$cabinet = $em->getRepository(Cabinet::class)->findOneBy([]);
		if (!$cabinet) {
			return $this->json(['nom' => 'null']);
		}

		return $this->json(['nom' => $cabinet->getNom(), 'type' => $cabinet->getType()]);
	}

}
