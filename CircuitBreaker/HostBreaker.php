<?php

namespace RedirectionIO\Client\ProxySymfony\CircuitBreaker;

use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
final class HostBreaker implements CircuitBreakerInterface
{
    private $excludedHosts;

    public function __construct(array $excludedHosts = [])
    {
        $this->excludedHosts = $excludedHosts;
    }

    public function shouldNotProcessRequest(Request $request): bool
    {
        $pathInfo = $request->getHost();

        foreach ($this->excludedHosts as $excludedHost) {
            if ($pathInfo === $excludedHost) {
                return true;
            }
        }

        return false;
    }
}
