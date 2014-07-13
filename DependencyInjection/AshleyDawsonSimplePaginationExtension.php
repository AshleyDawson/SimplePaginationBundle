<?php

namespace AshleyDawson\SimplePaginationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

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
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}