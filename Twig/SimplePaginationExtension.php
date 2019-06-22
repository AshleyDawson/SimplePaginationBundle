<?php

namespace AshleyDawson\SimplePaginationBundle\Twig;

use AshleyDawson\SimplePagination\Pagination;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class SimplePaginationExtension
 *
 * @package AshleyDawson\SimplePaginationBundle\Twig
 * @author Ashley Dawson <ashley@ashleydawson.co.uk>
 */
class SimplePaginationExtension extends AbstractExtension
{
    /**
     * @var string
     */
    private $defaultTemplate;

    /**
     * Constructor
     *
     * @param string $defaultTemplate
     */
    public function __construct($defaultTemplate)
    {
        $this->defaultTemplate = $defaultTemplate;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction(
                'simple_pagination_render',
                array($this, 'render'),
                array(
                    'is_safe' => array('html'),
                    'needs_environment' => true
                )
            ),
        );
    }

    /**
     * Render the pagination
     *
     * @param Environment $environment Will be automatically injected by Twig
     * @param Pagination $pagination
     * @param string $routeName
     * @param string $pageParameterName
     * @param array $queryParameters
     * @param string|null $template If null is passed then the default template is used
     * @return string
     */
    public function render(
        Environment $environment, Pagination $pagination, $routeName, $pageParameterName = 'page', array $queryParameters = array(), $template = null)
    {
        if (null === $template) {
            $template = $this->defaultTemplate;
        }

        return $environment->render($template, array(
            'pagination' => $pagination,
            'routeName' => $routeName,
            'pageParameterName' => $pageParameterName,
            'queryParameters' => $queryParameters,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ashley_dawson_simple_pagination_extension';
    }
}
