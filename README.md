Simple Pagination Bundle
========================

[![Build Status](https://travis-ci.org/AshleyDawson/SimplePaginationBundle.svg?branch=master)](https://travis-ci.org/AshleyDawson/SimplePaginationBundle)

[![knpbundles.com](http://knpbundles.com/AshleyDawson/SimplePaginationBundle/badge-short)](http://knpbundles.com/AshleyDawson/SimplePaginationBundle)

Symfony 2 bundle for the [Simple Pagination](https://github.com/AshleyDawson/SimplePagination) library.

Installation
------------

You can install the Simple Pagination Bundle via [Composer](https://getcomposer.org/). To do that, simply `require` the 
package in your `composer.json` file like so:

```json
{
    "require": {
        "ashleydawson/simple-pagination-bundle": "^1.0"
    }
}
```

Run `composer update` to install the package. Then you'll need to register the bundle in your `app/AppKernel.php`:

```php
$bundles = array(
    // ...
    new AshleyDawson\SimplePaginationBundle\AshleyDawsonSimplePaginationBundle(),
);
```

Basic Usage
-----------

The simplest collection we can use the paginator service on is an array. Please see below for an extremely
simple example of the paginator operating on an array. This shows the service paginating over an array of 
12 items.

```php
namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
    public function indexAction()
    {
        // Get the paginator service from the container
        $paginator = $this->get('ashley_dawson_simple_pagination.paginator');

        // Build a mock set of items to paginate over
        $items = array(
            'Banana',
            'Apple',
            'Cherry',
            'Lemon',
            'Pear',
            'Watermelon',
            'Orange',
            'Grapefruit',
            'Blackcurrant',
            'Dingleberry',
            'Snosberry',
            'Tomato',
        );

        // Set the item total callback, simply returning the total number of items
        $paginator->setItemTotalCallback(function () use ($items) {
            return count($items);
        });

        // Add the slice callback, simply slicing the items array using $offset and $length
        $paginator->setSliceCallback(function ($offset, $length) use ($items) {
            return array_slice($items, $offset, $length);
        });

        // Perform the pagination, passing the current page number from the request
        $pagination = $paginator->paginate((int)$this->get('request')->query->get('page', 1));

        // Pass the pagination object to the view for rendering
        return $this->render('AcmeDemoBundle:Welcome:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }
}
```

And in the twig view, it looks like this:

```twig
...

{# Iterate over items for the current page, rendering each one #}
<ul>
    {% for item in pagination.items %}
        <li>{{ item }}</li>
    {% endfor %}
</ul>

{# Iterate over the page list, rendering the page links #}
<div>
    {% for page in pagination.pages %}
        <a href="?page={{ page }}">{{ page }}</a> |
    {% endfor %}
</div>

...
```

You can override the "items per page" and "pages in range" options at runtime by passing values to the paginator like this:
 
```php
// ...

$paginator
    ->setItemsPerPage(20)
    ->setPagesInRange(5)
;

$pagination = $paginator->paginate((int)$this->get('request')->query->get('page', 1));

// ...
```

Please note that this is **a very simple example**, some advanced use-cases and interfaces are coming up (see below).

Doctrine Example
----------------

I've expanded the example above to use [Doctrine](http://www.doctrine-project.org/) instead of a static array of items:

```php
namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
    public function indexAction()
    {
        // Get the paginator service from the container
        $paginator = $this->get('ashley_dawson_simple_pagination.paginator');

        // Create a Doctrine query builder
        $manager = $this->getDoctrine()->getManager();
        $query = $manager->createQueryBuilder();

        // Build the initial query, including any special filters
        $query
            ->from('AcmeDemoBundle:Film', 'f')
            ->where('f.releaseAt > :threshold')
            ->setParameter('threshold', new \DateTime('1980-08-16'))
        ;

        // Pass the item total callback
        $paginator->setItemTotalCallback(function () use ($query) {

            // Run the count of all records
            $query
                ->select('COUNT(f.id)')
            ;

            // Return the total item count
            return (int)$query->getQuery()->getSingleScalarResult();
        });

        // Pass the slice callback
        $paginator->setSliceCallback(function ($offset, $length) use ($query) {

            // Select and slice the data
            $query
                ->select('f')
                ->setFirstResult($offset)
                ->setMaxResults($length)
            ;

            // Return the collection
            return $query->getQuery()->getResult();
        });

        // Finally, paginate using the current page number
        $pagination = $paginator->paginate((int)$this->get('request')->query->get('page', 1));

        // Pass the pagination object to the view
        return $this->render('AcmeDemoBundle:Welcome:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }
}
```

And in the twig view, it looks like this:

```twig
...

{# Iterate over films (Doctrine results) for the current page, rendering each one #}
<ul>
    {% for film in pagination.items %}
        <li>
            <strong>{{ film.title }}</strong>
            <time>{{ film.releaseAt | date('jS F, Y') }}</time>
        </li>
    {% endfor %}
</ul>

{# Use the pagination view helper to render the page navigation #}
<div>
    {{ simple_pagination_render(
        pagination, 
        '_welcome', 
        'page', 
        app.request.query.all
    ) }}
</div>

...
```

Configuration
-------------

You can configure the Simple Pager Bundle from `app/config/config.yml` with the following **optional** parameters:

```yaml
ashley_dawson_simple_pagination:
    defaults:
        items_per_page: 10
        pages_in_range: 5
        template: AshleyDawsonSimplePaginationBundle:Pagination:default.html.twig
```

Twig Function
-------------

I've provided a handy twig function to render the built in pagination template. The default template
can be configured in your `app/config/config.yml` or simply overridden as an argument in the twig function.

The arguments passed to the twig function are as follows:

```
simple_pagination_render(pagination : Pagination, routeName : string, [pageParameterName : string = 'page'], [queryParameters : array = array()], [template : string | null = null])
```

A brief description of each argument is:

* pagination: The `AshleyDawson\SimplePagination\Pagination` object returned by `AshleyDawson\SimplePagination\Paginator::paginate()`
* routeName: Route name to be passed to the navigation `{{ path() }}` function, defined in your routing config
* pageParameterName: The name of the page number parameter in your request (optional)
* queryParameters: The array of query parameters to append to the path (optional)
* template: The template you'd like to use to override the default (optional)

An exhaustive twig view example is as follows:

```twig
<div>
    {{ simple_pagination_render(
        pagination, 
        '_welcome', 
        'page', 
        app.request.query.all, 
        'AcmeBundle:Default:pagination.html.twig'
    ) }}
</div>
```

A better example, with the accompanying item list:

```twig
<div>
    <ul>
        {% for item in pagination.items %}
            <li>{{ item }}</li>
        {% endfor %}
    </ul>
</div>

<div>
    {{ simple_pagination_render(pagination, 'my_route_name', 'page', app.request.query.all) }}
</div>
```

Custom Service
--------------

If you'd like to define the paginator as a custom service, please use the following
service container configuration.

In YAML:

```yml

services:

  my_paginator:
    class: AshleyDawson\SimplePagination\Paginator
    calls:
      - [ setItemsPerPage, [ 10 ] ]
      - [ setPagesInRange, [ 5 ] ]

```

or in XML:

```xml
<services>
    <service id="my_paginator" class="AshleyDawson\SimplePagination\Paginator">
        <call method="setItemsPerPage">
            <argument>10</argument>
        </call>
        <call method="setPagesInRange">
            <argument>5</argument>
        </call>
    </service>
</services>
```

Then use it in your controllers like this:

```php
// Get my paginator service from the container
$paginator = $this->get('my_paginator');

// ...
```
