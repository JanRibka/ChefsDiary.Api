<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Middleware;

use JR\ChefsDiary\Config;
use Slim\Routing\RouteContext;
use JR\ChefsDiary\Enums\HttpStatusCode;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use JR\ChefsDiary\Services\Implementation\RequestService;

class RateLimitMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly RequestService $requestService,
        private readonly Config $config,
        private readonly RateLimiterFactory $rateLimiterFactory
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $clientIp = $this->requestService->getClientIp($request, $this->config->get('trusted_proxies'));
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $limiter = $this->rateLimiterFactory->create($route->getName() . '_' . $clientIp);
        // TODO: KRoute, kte to používám musím zadat name
        if ($limiter->consume()->isAccepted() === false) {
            return $this->responseFactory->createResponse(HttpStatusCode::TOO_MANY_REQUESTS->value, 'tooManyRequests');
        }

        return $handler->handle($request);
    }
}
