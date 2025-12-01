<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
	#[Route('/profil', name: 'app_user_profile')]
	public function profile(Request $request, EntityManagerInterface $em): Response
	{
		/** @var User $user */
		$user = $this->getUser();

		if ($request->isMethod('POST')) {

			// ⚠️ On NE modifie PAS l’email ici
			$user->setNom($request->request->get('nom'));
			$user->setPrenom($request->request->get('prenom'));

			$em->flush();

			$this->addFlash('success', 'Profil mis à jour avec succès !');
		}

		return $this->render('user/profile.html.twig', [
			'user' => $user,
		]);
	}
}
