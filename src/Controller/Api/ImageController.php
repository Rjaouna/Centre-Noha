<?php

namespace App\Controller\Api;

use App\Entity\Image;
use App\Entity\FicheClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/image')]
class ImageController extends AbstractController
{
	#[Route('/upload/{id}', name: 'app_image_upload', methods: ['POST'])]
	public function upload(Request $request, FicheClient $ficheClient, EntityManagerInterface $em): JsonResponse
	{
		$file = $request->files->get('image')['imageFile'] ?? null;


		if (!$file) {
			return new JsonResponse([
				'status' => 'error',
				'message' => 'Aucun fichier reçu'
			], 400);
		}

		$image = new Image();
		$image->setClient($ficheClient);
		$image->setImageFile($file); // Vich gère tout

		$em->persist($image);
		$em->flush();

		return new JsonResponse([
			'status' => 'success',
			'message' => 'Image uploadée',
			'fileName' => $image->getFileName()
		]);
	}
}
