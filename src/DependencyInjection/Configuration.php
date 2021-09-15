<?php

namespace Intracto\ElasticSynonymBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('intracto_elastic_synonym');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('synonym_configs')->info("Create a config for each synonym file you want to manage")
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->info('A convenient name for the end-user to recognize this config')->isRequired()->end()
                            ->scalarNode('file')->info('The synonym file relative to the elasticsearch config file')->isRequired()->end()
                            ->arrayNode('indices')->info('Index names or aliases to update')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        ;
        return $treeBuilder;
    }
}