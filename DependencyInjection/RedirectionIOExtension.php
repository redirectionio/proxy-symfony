<?php

namespace RedirectionIO\Client\ProxySymfony\DependencyInjection;

use RedirectionIO\Client\Sdk\Client;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class RedirectionIOExtension extends Extension
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
            ->replaceArgument(0, $connections)
        ;
    }
}