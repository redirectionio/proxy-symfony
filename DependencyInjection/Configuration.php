<?php

namespace RedirectionIO\Client\ProxySymfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('redirection_io');

        $rootNode
            ->children()
                ->arrayNode('connections')
                    ->normalizeKeys(false)
                    ->useAttributeAsKey('name')
                    ->addDefaultChildrenIfNoneSet('default')
                    ->prototype('scalar')
                        ->info('a TCP or unix socket')
                        ->example('tcp://127.0.0.1:20301 or unix:///var/run/redirectionio_agent.sock')
                        ->isRequired()
                        ->cannotBeEmpty()
                        ->defaultValue('tcp://127.0.0.1:20301')
                    ->end()
                ->end()
                ->booleanNode('debug')
                    ->info('Throw exception if something wrong happens')
                    ->defaultValue('%kernel.debug%')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
