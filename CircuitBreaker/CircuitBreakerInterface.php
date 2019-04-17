<?php

namespace RedirectionIO\Client\ProxySymfony\CircuitBreaker;

use Symfony\Component\HttpFoundation\Request;

interface CircuitBreakerInterface
{
    public function shouldNotProcessRequest(Request $request): bool;
}
