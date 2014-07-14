<?php

namespace AshleyDawson\SimplePaginationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;

/**
 * Class AshleyDawsonSimplePaginationExtension
 *
 * @package AshleyDawson\SimplePaginationBundle\DependencyInjection
 * @author Ashley Dawson <ashley@ashleydawson.co.uk>
 */
class AshleyDawsonSimplePaginationExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $processor = new Processor();

        $config = $processor->processConfiguration($configuration, $config);

        $container->setParameter('ashley_dawson_simple_pagination.defaults.items_per_page',
            $config['defaults']['items_per_page']);

        $container->setParameter('ashley_dawson_simple_pagination.defaults.pages_in_range',
            $config['defaults']['pages_in_range']);

        $container->setParameter('ashley_dawson_simple_pagination.defaults.template',
            $config['defaults']['template']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}