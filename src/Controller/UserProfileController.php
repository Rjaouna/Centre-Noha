<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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


	#[Route('/profil/change-password', name: 'app_change_password', methods: ['POST'])]
	public function changePassword(
		Request $request,
		UserPasswordHasherInterface $passwordHasher,
		EntityManagerInterface $em
	): Response {

		/** @var User $user */
		$user = $this->getUser();

		if (!$user) {
			throw $this->createAccessDeniedException();
		}

		$currentPassword = $request->request->get('current_password');
		$newPassword = $request->request->get('new_password');
		$confirmPassword = $request->request->get('confirm_password');

		// Vérifier mot de passe actuel
		if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
			$this->addFlash('danger', 'Mot de passe actuel incorrect.');
			return $this->redirectToRoute('app_user_profile');
		}

		// Vérifier confirmation
		if ($newPassword !== $confirmPassword) {
			$this->addFlash('danger', 'Les mots de passe ne correspondent pas.');
			return $this->redirectToRoute('app_user_profile');
		}

		// Vérifier complexité
		if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $newPassword)) {
			$this->addFlash('danger', 'Le mot de passe doit contenir au moins 8 caractères, avec lettres et chiffres.');
			return $this->redirectToRoute('app_user_profile');
		}

		// Hash + save
		$user->setPassword(
			$passwordHasher->hashPassword($user, $newPassword)
		);

		$em->flush();

		$this->addFlash('success', 'Mot de passe mis à jour avec succès.');

		return $this->redirectToRoute('app_logout');
	}
}
