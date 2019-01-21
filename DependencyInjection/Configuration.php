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
        $excludedPrefixes = ['/_wdt', '/_profiler', '/_error'];

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
                ->booleanNode('match_on_response')
                    ->info('Allow match on response status rules')
                    ->defaultValue(false)
                ->end()
                ->arrayNode('excluded_prefixes')
                    ->info('Exclude a set of prefixes from processing')
                    ->prototype('scalar')->end()
                    ->validate()
                        ->always()
                        ->then(function ($v) use ($excludedPrefixes) { return array_unique(array_merge($excludedPrefixes, $v)); })
                    ->end()
                    ->defaultValue($excludedPrefixes)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
