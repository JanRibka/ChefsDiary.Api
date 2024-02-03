<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use JR\ChefsDiary\Enums\HttpStatusCodeEnum;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use JR\ChefsDiary\Services\Contract\AuthServiceInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthServiceInterface $authService
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($user = $this->authService->getUser()) {

        }
        // return $this->responseFactory->createResponse()->withStatus(HttpStatusCodeEnum::FOUND->value);
    }
}