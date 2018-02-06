Psr11ServiceProvider
====================

This service provider registers a PSR-11 compatible container as a service
inside a [Silex](https://github.com/silexphp/Silex) application.

PSR-11 enables developers to write code that is aware of the service container
without coupling it to a specific container implementation, thus allowing to
switch to another service container more easily.

[![Build Status](https://travis-ci.org/derrabus/silex-psr11-provider.svg?branch=master)](https://travis-ci.org/derrabus/silex-psr11-provider)


Installation
------------

Use [Composer](https://getcomposer.org/) to install the package.

```
composer require derrabus/silex-psr11-provider
```


Usage
-----

Once you have registered the service provider, you can either access the
container as service `service_container` or as a controller argument by using
`Psr\Container\ContainerInterface` as type hint.


Examples
--------

```php

use Psr\Container\ContainerInterface;
use Rabus\Psr11ServiceProvider\Psr11ServiceProvider;
use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SomeController
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function someAction()
    {
        return new RedirectResponse(
            $this->container->get('url_generator')->generate('home')
        );
    }
}

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new Psr11ServiceProvider());

$app['some_controller'] = function ($app) {
    // Inject a PSR-11 container instead of the $app
    return new SomeController($app['service_container']);
};

$app->get('/', function() {
    return 'Home.';
})->bind('home');

$app->get('/test1', 'some_controller:someAction');

// Type-hint the PSR-11 container instead of the $app
$app->get('/test2', function (ContainerInterface $container) {
    new RedirectResponse($container->get('url_generator')->generate('home'));
});

```
