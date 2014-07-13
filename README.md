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

Configuration
-------------

You can configure the Simple Pager Bundle from `app/config/config.yml` with the following parameters:

```yml
ashley_dawson_simple_pagination:
  defaults:
    items_per_page: 10
    pages_in_range: 5
```

Basic Usage
-----------
