<?php

namespace RedirectionIO\Client\ProxySymfony\EventListener;

use RedirectionIO\Client\Sdk\Client;
use RedirectionIO\Client\Sdk\HttpMessage\Request;
use RedirectionIO\Client\Sdk\HttpMessage\Response;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;

class RequestResponseListener
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $this->findRedirect($event->getRequest());

        $response = $event->getRequest()->get('redirectionio_response');

        if (null === $response) {
            return;
        }

        $response->getStatusCode() === 410
            ? $event->setResponse((new SymfonyResponse())->setStatusCode(410))
            : $event->setResponse(new SymfonyRedirectResponse($response->getLocation(), $response->getStatusCode()));

        return true;
    }

    public function onKernelTerminate(PostResponseEvent $event)
    {
        $request = $this->createSdkRequest($event->getRequest());
        $response = $event->getRequest()->get('redirectionio_response');

        if (null === $response) {
            $response = new Response($event->getResponse()->getStatusCode());
        }

        return $this->client->log($request, $response);
    }

    private function findRedirect(SymfonyRequest $symfonyRequest)
    {
        $response = $this->client->findRedirect(
            $this->createSdkRequest($symfonyRequest)
        );

        $symfonyRequest->attributes->set('redirectionio_response', $response);
    }

    private function createSdkRequest(SymfonyRequest $symfonyRequest)
    {
        return new Request(
            $symfonyRequest->getHttpHost(),
            $symfonyRequest->getPathInfo(),
            $symfonyRequest->headers->get('User-Agent'),
            $symfonyRequest->headers->get('Referer'),
            $symfonyRequest->getScheme()
        );
    }
}
