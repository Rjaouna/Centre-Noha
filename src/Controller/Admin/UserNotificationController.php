<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

#[Route('/admin/user')]
class UserNotificationController extends AbstractController
{
	#[Route('/{id}/send-access', name: 'admin_user_send_access', methods: ['POST'])]
	public function sendAccess(
		User $user,
		Request $request,
		UserPasswordHasherInterface $passwordHasher,
		EntityManagerInterface $em,
		MailerInterface $mailer
	): JsonResponse {
		// 🔐 Sécurité CSRF
		if (!$this->isCsrfTokenValid('send_access', $request->headers->get('X-CSRF-TOKEN'))) {
			return new JsonResponse(['message' => 'Token CSRF invalide'], 403);
		}

		try {
			// 🔑 Mot de passe temporaire
			$plainPassword = bin2hex(random_bytes(4));

			$user->setPassword(
				$passwordHasher->hashPassword($user, $plainPassword)
			);

			

			$em->flush();

			// 📧 Email pro
			$email = (new TemplatedEmail())
				->from('contact@soinboard.com')
				->to($user->getEmail())
				->subject('Vos accès à la plateforme')
				->htmlTemplate('emails/access.html.twig')
				->context([
					'user' => $user,
					'password' => $plainPassword,
				]);

			$mailer->send($email);

			return new JsonResponse([
				'success' => true,
				'message' => 'Accès envoyés avec succès'
			]);
		} catch (\Throwable $e) {
			return new JsonResponse([
				'success' => false,
				'message' => $e->getMessage(), // 👈 ESSENTIEL
			], 500);
		}
	}
}
