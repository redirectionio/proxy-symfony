<?php

namespace RedirectionIO\Client\ProxySymfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @internal
 */
final class Configuration implements ConfigurationInterface
{
    const ROOT_NAME = 'redirection_io';
    
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(self::ROOT_NAME);
        $rootNode = $this->getRootNode($treeBuilder);
        $excludedPrefixes = ['/_wdt', '/_profiler', '/_error'];

        $rootNode
            ->children()
                ->scalarNode('project_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('Your project key that can be found at: https://redirection.io/manager/<organization>/<project>/instances')
                ->end()
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
                ->booleanNode('persist')
                    ->info('Persist client connections to be reuse on other requests')
                    ->defaultTrue()
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
                ->arrayNode('excluded_hosts')
                    ->info('Exclude a set of hosts from processing')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
    
    /**
     * Returns the root node of TreeBuilder with backwards compatibility with Symfony < 4.3
     */
    private function getRootNode(TreeBuilder $treeBuilder): NodeDefinition
    {
        if (\method_exists($treeBuilder, 'getRootNode')) {
            return $treeBuilder->getRootNode();
        } else {
            return $treeBuilder->root(self::ROOT_NAME);
        }
    }
}
