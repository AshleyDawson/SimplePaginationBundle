<?php

namespace AshleyDawson\SimplePaginationBundle\Tests\DependencyInjection;

use AshleyDawson\SimplePaginationBundle\DependencyInjection\AshleyDawsonSimplePaginationExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class AshleyDawsonSimplePaginationExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadWithoutConfiguration()
    {
        $container = $this->createContainer();
        $container->registerExtension(new AshleyDawsonSimplePaginationExtension());
        $container->loadFromExtension('ashley_dawson_simple_pagination', array());
        $this->compileContainer($container);

        $expectedTags = array(
            array(
                'id' => 'dump',
                'template' => '@Debug/Profiler/dump.html.twig',
                'priority' => 240,
            ),
        );

        $this->assertSame(10, $container->getParameter('ashley_dawson_simple_pagination.defaults.items_per_page'));
        $this->assertSame(5, $container->getParameter('ashley_dawson_simple_pagination.defaults.pages_in_range'));
        $this->assertSame('AshleyDawsonSimplePaginationBundle:Pagination:default.html.twig', $container->getParameter('ashley_dawson_simple_pagination.defaults.template'));

        $this->assertTrue($container->has('ashley_dawson_simple_pagination.paginator'));
        $this->assertTrue($container->has('ashley_dawson_simple_pagination.twig_extension'));
    }

    private function createContainer()
    {
        $container = new ContainerBuilder(new ParameterBag(array(
            'kernel.cache_dir' => __DIR__,
            'kernel.root_dir' => __DIR__.'/Fixtures',
            'kernel.charset' => 'UTF-8',
            'kernel.debug' => true,
            'kernel.bundles' => array('AshleyDawsonSimplePaginationBundle' => 'AshleyDawson\\SimplePaginationBundle\\AshleyDawsonSimplePaginationBundle'),
        )));

        return $container;
    }

    private function compileContainer(ContainerBuilder $container)
    {
        $container->getCompilerPassConfig()->setOptimizationPasses(array());
        $container->getCompilerPassConfig()->setRemovingPasses(array());
        $container->compile();
    }
}
