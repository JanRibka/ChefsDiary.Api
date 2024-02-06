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
use JR\ChefsDiary\Services\Implementation\RequestService;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly RequestService $requestService,
    ) {
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ValidationException $ex) {
            $response = $this->responseFactory->createResponse();

            $x = $this->requestService->isXhr($request);
        }
        // try {
        //     return $handler->handle($request);
        // } catch (ValidationException $ex) {
        //     $response = $this->responseFactory->createResponse();

        //     // $referer = $request->getServerParams()['HTTP_REFERER'];

        //     return $response->withStatus(HttpStatusCodeEnum::FOUND->value);

        //     // return $response->getHeader('Location', $referer);

        // }
        $response = $this->responseFactory->createResponse();
        return $response->withStatus(HttpStatusCodeEnum::FOUND->value);
    }
}