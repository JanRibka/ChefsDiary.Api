<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Middleware;

use Exception;
use RuntimeException;
use JR\ChefsDiary\Enums\UserRoleEnum;
use JR\ChefsDiary\Enums\HttpStatusCode;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use JR\ChefsDiary\Services\Contract\TokenServiceInterface;

class VerifyAuthenticationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly TokenServiceInterface $tokenService,
        private array $userRoles = []
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $this->tokenService->verifyJWT($request, $handler);
        $statusCode = $response->getStatusCode();

        if ($statusCode === HttpStatusCode::UNAUTHORIZED->value) {
            return $this->responseFactory->createResponse($statusCode);
        }

        return $handler->handle($request);
    }

    /**
     * @param UserRoleEnum[] $userRoles
     */
    public static function processWithParameter(array $userRoles)
    {
        return function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($userRoles) {
            $middleware = new self($this->responseFactory, $this->tokenService, $userRoles);
            return $middleware->process($request, $handler);
        };
    }
}
