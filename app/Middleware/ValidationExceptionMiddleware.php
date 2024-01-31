<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use JR\ChefsDiary\Enums\HttpStatusCodeEnum;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use JR\ChefsDiary\Exception\ValidationException;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory
    ) {
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ValidationException $ex) {
            $response = $this->responseFactory->createResponse();

            // $referer = $request->getServerParams()['HTTP_REFERER'];

            return $response->withStatus(HttpStatusCodeEnum::FOUND->value);

            // return $response->getHeader('Location', $referer);

        }
    }
}