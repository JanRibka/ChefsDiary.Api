<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Middleware;

use JR\ChefsDiary\Enums\HttpStatusCode;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class VerifyEmailMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ResponseFactoryInterface $responseFactory)
    {
    }
    // TODO: Tentop middleware by měl kontrolovat u kždého requestu, zda je uživatel ověřený. Pokud ne, zobrazí se stránka pro ověření emailu
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $request->getAttribute('user');

        if ($user?->getVerifiedAt()) {
            return $handler->handle($request);
        }

        // TODO: Zkusit ud2lat přesměrování tady, pokud nemám ověřený email
        return $this->responseFactory->createResponse(HttpStatusCode::FOUND->value);
        // return $this->responseFactory->createResponse(302)->withHeader('Location', '/verify');
    }
}