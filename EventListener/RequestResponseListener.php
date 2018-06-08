<?php

namespace RedirectionIO\Client\ProxySymfony\EventListener;

use RedirectionIO\Client\Sdk\Exception\ExceptionInterface;
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
    private $excludedPrefixes;

    public function __construct(Client $client, array $excludedPrefixes = [])
    {
        $this->client = $client;
        $this->excludedPrefixes = $excludedPrefixes;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

        if ($this->isExcludedPrefix($request->getPathInfo())) {
            return;
        }

        $response = $this->client->findRedirect($this->createSdkRequest($request));
        $request->attributes->set('redirectionio_response', $response);

        if (!$response) {
            return;
        }

        410 === $response->getStatusCode()
            ? $event->setResponse((new SymfonyResponse())->setStatusCode(410))
            : $event->setResponse(new SymfonyRedirectResponse($response->getLocation(), $response->getStatusCode()));
    }

    public function onKernelTerminate(PostResponseEvent $event)
    {
        if ($this->isExcludedPrefix($event->getRequest()->getPathInfo())) {
            return;
        }

        $response = $event->getRequest()->attributes->get('redirectionio_response');

        if (!$response) {
            $response = new Response($event->getResponse()->getStatusCode());
        }

        $request = $this->createSdkRequest($event->getRequest());

        try {
            $this->client->log($request, $response);
        } catch (ExceptionInterface $exception) {
            // do nothing
        }
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

    private function isExcludedPrefix($url): bool
    {
        foreach ($this->excludedPrefixes as $excludedPrefix) {
            if (0 === strpos($url, $excludedPrefix)) {
                return true;
            }
        }

        return false;
    }
}
