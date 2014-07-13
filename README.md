Simple Pagination Bundle
========================

Symfony 2 bundle for the [Simple Pagination](https://github.com/AshleyDawson/SimplePagination) library.

Installation
------------

You can install the Simple Pagination Bundle via [Composer](https://getcomposer.org/). To do that, simply `require` the 
package in your `composer.json` file like so:

```json
{
    "require": {
        "ashleydawson/simple-pagination-bundle": "1.0.*"
    }
}
```

Then run `composer update` to install the package.

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
        $paginator = $this->get('ashley_dawson_simple_pagination.paginator');

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

        $paginator->setItemTotalCallback(function () use ($items) {
            return count($items);
        });

        $paginator->setSliceCallback(function ($offset, $length) use ($items) {
            return array_slice($items, $offset, $length);
        });

        $pagination = $paginator->paginate((int)$this->get('request')->query->get('page', 1));

        return $this->render('AcmeDemoBundle:Welcome:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }
}
```

And in the twig view, it looks like this:

```twig
...

<ul>
    {% for item in pagination.items %}
        <li>{{ item }}</li>
    {% endfor %}
</ul>

<div>
    {% for page in pagination.pages %}
        <a href="?page={{ page }}">{{ page }}</a> |
    {% endfor %}
</div>

...
```

Please note that this is **a very simple example**, some more advanced use-cases and interfaces are coming up.

Configuration
-------------

You can configure the Simple Pager Bundle from `app/config/config.yml` with the following **optional** parameters:

```yaml
ashley_dawson_simple_pagination:
  defaults:
    items_per_page: 10
    pages_in_range: 5
```

Custom Service
--------------

If you'd like to define the paginator as a custom service, please use the following
service container configuration.

In Yaml:

```yaml

services:

  my_paginator:
    class: AshleyDawson\SimplePagination\Paginator
    calls:
      - setItemsPerPage, [ 10 ]
      - setPagesInRange, [ 5 ]

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