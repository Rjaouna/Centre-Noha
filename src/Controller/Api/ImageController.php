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
	public function upload(
		Request $request,
		FicheClient $ficheClient,
		EntityManagerInterface $em
	): JsonResponse {
		$file = $request->files->get('image')['imageFile'] ?? null;

		if (!$file) {
			return new JsonResponse([
				'success' => false,
				'message' => 'Aucun fichier reçu'
			], 400);
		}

		$image = new Image();
		$image->setClient($ficheClient);
		$image->setImageFile($file); // VichUploader

		$em->persist($image);
		$em->flush();

		return new JsonResponse([
			'success'  => true,
			'id'       => $image->getId(),
			'fileName' => $image->getFileName()
		]);
	}

	#[Route('/delete/{id}', name: 'app_image_delete', methods: ['DELETE'])]
	public function delete(Image $image, EntityManagerInterface $em): JsonResponse
	{
		$em->remove($image);
		$em->flush();

		return new JsonResponse(['success' => true]);
	}
}
