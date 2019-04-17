<?php

namespace RedirectionIO\Client\ProxySymfony;

use RedirectionIO\Client\ProxySymfony\DependencyInjection\CompilerPass\CircuitBreakerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class RedirectionIOBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new CircuitBreakerPass());
    }
}
