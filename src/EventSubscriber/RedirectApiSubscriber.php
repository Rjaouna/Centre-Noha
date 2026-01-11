<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RedirectApiSubscriber implements EventSubscriberInterface
{
	public function __construct(private UrlGeneratorInterface $urlGenerator) {}

	public static function getSubscribedEvents(): array
	{
		return [KernelEvents::REQUEST => ['onKernelRequest', 20]];
	}

	public function onKernelRequest(RequestEvent $event): void
	{
		if (!$event->isMainRequest()) {
			return;
		}

		$request = $event->getRequest();

		if (!str_starts_with($request->getPathInfo(), '/api')) {
			return;
		}

		// ✅ On ne redirige que si c'est une "navigation" navigateur (document)
		$secFetchMode = $request->headers->get('sec-fetch-mode'); // navigate/cors/no-cors...
		$secFetchDest = $request->headers->get('sec-fetch-dest'); // document/empty/...
		$isBrowserNavigation = ($secFetchMode === 'navigate') || ($secFetchDest === 'document');

		// Autre heuristique : le navigateur accepte surtout du HTML
		$accept = $request->headers->get('Accept', '');
		$wantsHtml = str_contains($accept, 'text/html');
		$wantsJson = str_contains($accept, 'application/json') || $request->getRequestFormat() === 'json';

		// ✅ On redirige uniquement sur des GET "document" (ou html), pas sur fetch/api
		if ($request->isMethod('GET') && ($isBrowserNavigation || ($wantsHtml && !$wantsJson))) {
			$event->setResponse(new RedirectResponse($this->urlGenerator->generate('app_home')));
		}
	}
}
