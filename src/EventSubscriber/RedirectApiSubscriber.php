<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RedirectApiSubscriber implements EventSubscriberInterface
{
	public function __construct(private UrlGeneratorInterface $urlGenerator) {}

	public static function getSubscribedEvents(): array
	{
		return [KernelEvents::REQUEST => ['onKernelRequest', 20]];
	}

	public function onKernelRequest(RequestEvent $event): void
	{
		if (!$event->isMainRequest()) return;

		$request = $event->getRequest();
		if (!str_starts_with($request->getPathInfo(), '/api')) return;

		// Évite de casser les appels AJAX/JSON
		$accept = $request->headers->get('Accept', '');
		if ($request->isXmlHttpRequest() || str_contains($accept, 'application/json')) return;

		$event->setResponse(new RedirectResponse($this->urlGenerator->generate('app_home')));
	}
}
