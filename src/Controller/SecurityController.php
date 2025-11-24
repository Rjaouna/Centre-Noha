<?php

namespace App\Controller;

use App\Form\LoginFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
	#[Route('/login', name: 'app_login')]
	public function login(AuthenticationUtils $authenticationUtils, CsrfTokenManagerInterface $csrf): Response
	{


		if ($this->getUser()) {

			return $this->redirectToRoute('app_home');
		}

		$form = $this->createForm(LoginFormType::class, null, [
			'csrf_token' => $csrf->getToken('authenticate'),
		]);

		return $this->render('security/login.html.twig', [
			'form' => $form->createView(),
			'error' => $authenticationUtils->getLastAuthenticationError(),
		]);
	}

	#[Route('/logout', name: 'app_logout')]
	public function logout(): void {}
}
