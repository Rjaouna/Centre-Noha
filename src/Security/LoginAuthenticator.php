<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
	use TargetPathTrait;

	public const LOGIN_ROUTE = 'app_login';

	public function __construct(
		private UrlGeneratorInterface $urlGenerator
	) {}

	public function authenticate(Request $request): Passport
	{
		// 🔥 RÉCUPÉRATION CORRECTE DES DONNÉES DU FORMULAIRE SYMFONY
		$data = $request->request->all('login_form');

		$email = $data['email'] ?? '';
		$password = $data['password'] ?? '';
		$csrfToken = $data['_token'] ?? '';

		// Sauvegarde du dernier email
		$request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

		return new Passport(
			new UserBadge($email),
			new PasswordCredentials($password),
			[
				new CsrfTokenBadge('authenticate', $csrfToken),
			]
		);
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
	{
		$session = $request->getSession();
		$targetPath = $this->getTargetPath($session, $firewallName);

		// Si c'est un endpoint API/JSON, on ignore la targetPath
		if ($targetPath && !str_starts_with($targetPath, '/api')) {
			$this->removeTargetPath($session, $firewallName);
			return new RedirectResponse($targetPath);
		}

		$this->removeTargetPath($session, $firewallName);
		return new RedirectResponse($this->urlGenerator->generate('app_home'));
	}


	protected function getLoginUrl(Request $request): string
	{
		return $this->urlGenerator->generate(self::LOGIN_ROUTE);
	}
}
