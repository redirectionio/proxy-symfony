<?php

namespace RedirectionIO\Client\ProxySymfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('redirectionio');

        $rootNode
            ->children()
                ->arrayNode('connections')
                    ->normalizeKeys(false)
                    ->useAttributeAsKey('name')
                    ->addDefaultChildrenIfNoneSet('default')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('host')
                                ->info('Agent IP or hostname')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->defaultValue('127.0.0.1')
                            ->end()
                            ->integerNode('port')
                                ->info('Agent port')
                                ->isRequired()
                                ->min(0)
                                ->defaultValue(20301)
                            ->end()
                    ->end()
                ->end() // connections
            ->end()
        ;

        return $treeBuilder;
    }
}
