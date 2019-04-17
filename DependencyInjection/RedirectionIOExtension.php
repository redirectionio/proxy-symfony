<?php

namespace RedirectionIO\Client\ProxySymfony\DependencyInjection;

use RedirectionIO\Client\ProxySymfony\CircuitBreaker\CircuitBreakerInterface;
use RedirectionIO\Client\ProxySymfony\CircuitBreaker\HostBreaker;
use RedirectionIO\Client\ProxySymfony\CircuitBreaker\PathInfoPrefixBreaker;
use RedirectionIO\Client\ProxySymfony\EventListener\RequestResponseListener;
use RedirectionIO\Client\Sdk\Client;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @internal
 */
final class RedirectionIOExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config['connections'] as $name => $connection) {
            $connections[$name] = $container->resolveEnvPlaceholders($connection);
        }

        $container
            ->getDefinition(Client::class)
            ->replaceArgument(0, $config['project_key'])
            ->replaceArgument(1, $connections)
            ->replaceArgument(3, $config['debug'])
        ;

        $container
            ->getDefinition(RequestResponseListener::class)
            ->replaceArgument(1, $config['match_on_response'])
        ;

        if ($config['excluded_prefixes']) {
            $container
                ->getDefinition(PathInfoPrefixBreaker::class)
                ->replaceArgument(0, $config['excluded_prefixes'])
            ;
        } else {
            $container->removeDefinition(PathInfoPrefixBreaker::class);
        }

        if ($config['excluded_hosts']) {
            $container
                ->getDefinition(HostBreaker::class)
                ->replaceArgument(0, $config['excluded_hosts'])
            ;
        } else {
            $container->removeDefinition(HostBreaker::class);
        }

        if (method_exists($container, 'registerForAutoconfiguration')) {
            $container
                ->registerForAutoconfiguration(CircuitBreakerInterface::class)
                ->addTag('redirectionio.circuit_breaker')
            ;
        }
    }
}
