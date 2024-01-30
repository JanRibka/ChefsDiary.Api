<?php

declare(strict_types=1);

namespace JR\ChefsDiary;

use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Interfaces\InvocationStrategyInterface;
use JR\ChefsDiary\Services\Contracts\IEntityManagerService;

class RouteEntityBindingStrategy implements InvocationStrategyInterface
{
    public function __construct(
        private readonly IEntityManagerService $entityManagerService,
        private readonly ResponseFactoryInterface $responseFactory
    ) {
    }



}