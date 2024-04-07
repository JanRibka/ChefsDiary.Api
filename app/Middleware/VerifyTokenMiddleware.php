<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Middleware;

use JR\ChefsDiary\Enums\HttpStatusCode;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use JR\ChefsDiary\Services\Contract\TokenServiceInterface;

class VerifyTokenMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly TokenServiceInterface $tokenService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $this->tokenService->verifyJWT($request, $handler);
        $statusCode = $response->getStatusCode();

        if ($statusCode !== HttpStatusCode::OK->value) {
            return $this->responseFactory->createResponse($statusCode);
        }

        return $handler->handle($request);
    }
}
