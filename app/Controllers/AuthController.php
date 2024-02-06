<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Controllers;

use JR\ChefsDiary\Entity\User\User;
use JR\ChefsDiary\Enums\HttpStatusCodeEnum;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use JR\ChefsDiary\RequestValidators\RequestValidatorFactoryInterface;
use JR\ChefsDiary\RequestValidators\Auth\RegisterUserRequestValidator;

class AuthController
{
    public function __construct(
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory
    ) {
    }


    public function register(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory->make(RegisterUserRequestValidator::class)
            ->validate(
                $request->getParsedBody()
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