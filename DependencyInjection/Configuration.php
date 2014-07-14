<?php

namespace AshleyDawson\SimplePaginationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package AshleyDawson\SimplePaginationBundle\DependencyInjection
 * @author Ashley Dawson <ashley@ashleydawson.co.uk>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ashley_dawson_simple_pagination');

        $rootNode
            ->children()
                ->arrayNode('defaults')
                ->addDefaultsIfNotSet()
                ->children()
                    ->integerNode('items_per_page')->defaultValue(10)->cannotBeEmpty()->end()
                    ->integerNode('pages_in_range')->defaultValue(5)->cannotBeEmpty()->end()
                    ->scalarNode('template')->defaultValue('AshleyDawsonSimplePaginationBundle:pagination:default.html.twig')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}