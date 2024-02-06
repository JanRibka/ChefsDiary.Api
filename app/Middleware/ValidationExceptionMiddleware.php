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
use JR\ChefsDiary\Shared\ResponseFormatter\ResponseFormatter;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly RequestService $requestService,
        private readonly ResponseFormatter $responseFormatter
    ) {
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ValidationException $ex) {
            $response = $this->responseFactory->createResponse();

            if ($this->requestService->isXhr($request)) {
                return $this->responseFormatter->asJson($response->withStatus(HttpStatusCodeEnum::UNPROCESSABLE_ENTITY->value), $ex->errors);
            }

            return $this->responseFormatter->asJson($response->withStatus($ex->getCode()), $ex->errors);
        }
    }
}