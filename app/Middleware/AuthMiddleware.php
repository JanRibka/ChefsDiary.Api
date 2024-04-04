<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Middleware;

use JR\ChefsDiary\Enums\HttpStatusCode;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use JR\ChefsDiary\DataObjects\Configs\AuthCookieConfig;
use JR\ChefsDiary\Services\Implementation\TokenService;
use JR\ChefsDiary\Services\Contract\AuthServiceInterface;
use JR\ChefsDiary\Services\Contract\CookieServiceInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthServiceInterface $authService,
        private readonly CookieServiceInterface $cookieService,
        private readonly AuthCookieConfig $authCookieConfig,
        private readonly TokenService $tokenService
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authCookie = $this->cookieService->get($this->authCookieConfig->name);

        if (!isset($authCookie)) {
            return $this->responseFactory->createResponse()->withStatus(HttpStatusCode::NO_CONTENT->value);
        }

        return $handler->handle($request);
    }
}