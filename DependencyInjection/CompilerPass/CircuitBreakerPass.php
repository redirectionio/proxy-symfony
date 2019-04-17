<?php

namespace RedirectionIO\Client\ProxySymfony\DependencyInjection\CompilerPass;

use RedirectionIO\Client\ProxySymfony\EventListener\RequestResponseListener;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @internal
 */
final class CircuitBreakerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(RequestResponseListener::class)) {
            return;
        }

        $definition = $container->getDefinition(RequestResponseListener::class);

        if (class_exists(TaggedIteratorArgument::class)) {
            $definition->replaceArgument(2, new TaggedIteratorArgument('redirectionio.circuit_breaker'));

            return;
        }

        $ids = [];
        foreach ($container->findTaggedServiceIds('redirectionio.circuit_breaker') as $id => $_) {
            $ids[] = new Reference($id);
        }
        $definition->replaceArgument(2, $ids);
    }
}
