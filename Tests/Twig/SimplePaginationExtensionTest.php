<?php

namespace AshleyDawson\SimplePaginationBundle\Tests\Twig;

use AshleyDawson\SimplePaginationBundle\Twig\SimplePaginationExtension;
use AshleyDawson\SimplePagination\Paginator;

class SimplePaginationExtensionTest extends \PHPUnit_Framework_TestCase
{
    private $pagination;

    public function setUp()
    {
        $items = array(
            'Banana',
            'Apple',
            'Cherry',
        );

        $paginator = new Paginator();
        $paginator
            ->setItemsPerPage(1)
            ->setPagesInRange(5);

        $paginator->setItemTotalCallback(function () use ($items) {
            return count($items);
        });

        $paginator->setSliceCallback(function ($offset, $length) use ($items) {
            return array_slice($items, $offset, $length);
        });

        $this->pagination = $paginator->paginate();
    }

    public function testExtension()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__.'/../fixtures/views');
        $twig = new \Twig_Environment($loader);

        $extension = new SimplePaginationExtension('default.html.twig');
        $html = $extension->render(
            $twig,
            $this->pagination,
            ''
        );

        $this->assertContains('<span class="page current">1</span>', $html);
        $this->assertContains('<span class="page next active">2</span>', $html);
        $this->assertContains('<span class="page last active">3</span>', $html);
    }

    public function testExtensionInTemplate()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__.'/../fixtures/views');
        $twig = new \Twig_Environment($loader);
        $twig->addExtension(new SimplePaginationExtension('default.html.twig'));

        $html = $twig->loadTemplate('pagination.html.twig')->render([
            'app' => ['request' => ['query' => ['all' => []]]],
            'pagination' => $this->pagination,
        ]);

        $this->assertContains('<span class="page current">1</span>', $html);
        $this->assertContains('<span class="page next active">2</span>', $html);
        $this->assertContains('<span class="page last active">3</span>', $html);
    }
}
