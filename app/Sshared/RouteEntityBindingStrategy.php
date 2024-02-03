<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Shared;

use ReflectionMethod;
use ReflectionFunctionAbstract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Interfaces\InvocationStrategyInterface;
use JR\ChefsDiary\Services\Contract\EntityManagerServiceInterface;

class RouteEntityBindingStrategy implements InvocationStrategyInterface
{
    public function __construct(
        private readonly EntityManagerServiceInterface $entityManagerService,
        private readonly ResponseFactoryInterface $responseFactory
    ) {
    }

    public function __invoke(
        callable $callable,
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $routeArguments
    ) {
    }

    public function createReflectionForCallable(callable $callable): ReflectionFunctionAbstract
    {
        return is_array($callable)
            ? new ReflectionMethod($callable[0], $callable[1])
            : new \ReflectionFunction($callable);
    }
}