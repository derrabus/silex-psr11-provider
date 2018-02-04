<?php

namespace Rabus\Psr11ServiceProvider;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ArgumentResolver implements ArgumentValueResolverInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return $argument->getType() === ContainerInterface::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield $this->container;
    }
}
