<?php

namespace RedirectionIO\Client\ProxySymfony\DependencyInjection;

use RedirectionIO\Client\Sdk\Client;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @see http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class RedirectionIOClientSymfonyExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // normalize
        $connections = [];
        foreach ($config['connections'] as $connectionName => $connection) {
            foreach ($connection as $key => $val) {
                $connections[$connectionName][$key] = $val;
            }
        }

        $container
            ->getDefinition(Client::class)
            ->replaceArgument(0, $connections);
    }

    public function getAlias()
    {
        return 'redirectionio';
    }
}
