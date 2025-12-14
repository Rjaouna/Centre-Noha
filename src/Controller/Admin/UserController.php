<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/users/notification')]
class UserController extends AbstractController
{
	#[Route('', name: 'admin_users', methods: ['GET'])]
	public function index(UserRepository $userRepository): Response
	{
		return $this->render('admin/users.html.twig', [
			'users' => $userRepository->findAll(),
		]);
	}
}
