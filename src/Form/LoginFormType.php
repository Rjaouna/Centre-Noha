<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class LoginFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			// Champ email
			->add('email', EmailType::class, [
				'label' => 'Adresse e-mail',
				'attr' => [
					'placeholder' => 'Votre e-mail',
					'class' => 'form-control rounded-3'
				],
			])

			// Champ mot de passe
			->add('password', PasswordType::class, [
				'label' => 'Mot de passe',
				'attr' => [
					'placeholder' => 'Votre mot de passe',
					'class' => 'form-control rounded-3'
				],
			])

			// Champ CSRF obligatoire pour l’authenticator
			->add('_csrf_token', HiddenType::class, [
				'data' => $options['csrf_token'],
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'csrf_token' => null,
		]);
	}
}
