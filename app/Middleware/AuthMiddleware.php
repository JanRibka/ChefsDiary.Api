<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use JR\ChefsDiary\Enums\HttpStatusCodeEnum;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use JR\ChefsDiary\DataObjects\AuthCookieConfig;
use JR\ChefsDiary\Services\Contract\AuthServiceInterface;
use JR\ChefsDiary\Services\Contract\AuthCookieServiceInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthServiceInterface $authService,
        private readonly AuthCookieServiceInterface $authCookieService,
        private readonly AuthCookieConfig $authCookieConfig
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authCookie = $this->authCookieService->getCookie($this->authCookieConfig->name);

        if (!isset($authCookie)) {
            return $this->responseFactory->createResponse()->withStatus(HttpStatusCodeEnum::NO_CONTENT->value);
        }

        return $handler->handle($request);
    }
}