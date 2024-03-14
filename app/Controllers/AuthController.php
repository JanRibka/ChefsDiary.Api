<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Controllers;

use JR\ChefsDiary\Enums\HttpStatusCodeEnum;
use JR\ChefsDiary\Enums\AuthAttemptStatusEnum;
use JR\ChefsDiary\DataObjects\RegisterUserData;
use JR\ChefsDiary\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use JustSteveKing\StatusCode\Http as HttpStatusCode;
use Psr\Http\Message\ServerRequestInterface as Request;
use JR\ChefsDiary\Services\Contract\AuthServiceInterface;
use JR\ChefsDiary\Shared\ResponseFormatter\ResponseFormatter;
use JR\ChefsDiary\RequestValidators\Auth\UserLoginRequestValidator;
use JR\ChefsDiary\RequestValidators\RequestValidatorFactoryInterface;
use JR\ChefsDiary\RequestValidators\Auth\RegisterUserRequestValidator;

class AuthController
{
    public function __construct(
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
        private readonly AuthServiceInterface $authService,
        private readonly ResponseFormatter $responseFormatter
    ) {
    }

    /**
     * Register new user
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     * @author Jan Ribka
     */
    public function register(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory->make(RegisterUserRequestValidator::class)
            ->validate(
                $request->getParsedBody()
            );

        $this->authService->register(
            new RegisterUserData(
                $data['login'],
                $data['password'],
                $data['userName']
            )
        );

        return $response->withStatus(HttpStatusCode::CREATED->value);
    }

    /**
     * Login user
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     * @author Jan Ribka
     */
    public function login(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory->make(UserLoginRequestValidator::class)->validate(
            $request->getParsedBody()
        );

        $status = $this->authService->attemptLogin($data);

        if ($status === AuthAttemptStatusEnum::FAILED) {
            throw new ValidationException(['unauthorized' => ['incorrectLoginPassword']], HttpStatusCode::UNAUTHORIZED->value);
        }

        if ($status === AuthAttemptStatusEnum::DISABLED) {
            throw new ValidationException(['unauthorized' => ['accessDenied']], HttpStatusCode::FORBIDDEN->value);
        }
        // TODO: Stahnout balicek na HTTP status code
        // TODO: Dvoufazove overeni
        // if ($status === AuthAttemptStatus::TWO_FACTOR_AUTH) {
        //     return $this->responseFormatter->asJson($response, ['two_factor' => true]);
        // }

        return $this->responseFormatter->asJson($response, $status);
    }

    // public function logout(Request $request, Response $response): Response
    // {

    // }
}