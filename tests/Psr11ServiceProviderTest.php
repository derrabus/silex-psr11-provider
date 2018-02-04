<?php

namespace Rabus\Psr11ServiceProvider;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class Psr11ServiceProviderTest extends TestCase
{
    public function testServiceRegistration()
    {
        $app = $this->bootApplication();

        /** @var ContainerInterface $actualContainer */
        $actualContainer = $app['service_container'];

        $this->assertInstanceOf(ContainerInterface::class, $actualContainer);
        $this->assertInstanceOf(HttpKernelInterface::class, $actualContainer->get('kernel'));
    }

    public function testControllerArgument()
    {
        $app = $this->bootApplication();

        $app->get('/test', function (ContainerInterface $container) {
            $this->assertInstanceOf(ContainerInterface::class, $container);
            $this->assertInstanceOf(HttpKernelInterface::class, $container->get('kernel'));

            return 'success';
        });

        $response = $app->handle(Request::create('/test'));
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('success', $response->getContent());
    }

    /**
     * @return Application
     */
    private function bootApplication()
    {
        $app = new Application();
        $app->register(new Psr11ServiceProvider());
        $app->boot();

        return $app;
    }
}
