<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Controllers;

use JR\ChefsDiary\Enums\HttpStatusCodeEnum;
use JR\ChefsDiary\DataObjects\RegisterUserData;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use JR\ChefsDiary\Services\Contract\AuthServiceInterface;
use JR\ChefsDiary\RequestValidators\RequestValidatorFactoryInterface;
use JR\ChefsDiary\RequestValidators\Auth\RegisterUserRequestValidator;

class AuthController
{
    public function __construct(
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
        private readonly AuthServiceInterface $authService
    ) {
    }


    public function register(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory->make(RegisterUserRequestValidator::class)
            ->validate(
                $request->getParsedBody()
            );

        // TODO: Tady bude transakce
        $this->authService->register(
            new RegisterUserData($data['login'], $data['password'])
        );

        return $response->withStatus(HttpStatusCodeEnum::CREATED->value);
    }

    // public function login(Request $request, Response $response): Response
    // {

    // }

    // public function logout(Request $request, Response $response): Response
    // {

    // }
}