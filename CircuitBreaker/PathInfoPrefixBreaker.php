<?php

namespace RedirectionIO\Client\ProxySymfony\CircuitBreaker;

use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
final class PathInfoPrefixBreaker implements CircuitBreakerInterface
{
    private $excludedPrefixes;

    public function __construct(array $excludedPrefixes = [])
    {
        $this->excludedPrefixes = $excludedPrefixes;
    }

    public function shouldNotProcessRequest(Request $request): bool
    {
        $pathInfo = $request->getPathInfo();

        foreach ($this->excludedPrefixes as $excludedPrefix) {
            if (0 === strpos($pathInfo, $excludedPrefix)) {
                return true;
            }
        }

        return false;
    }
}
