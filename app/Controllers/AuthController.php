<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Controllers;

use JR\ChefsDiary\Enums\HttpStatusCode;
use JR\ChefsDiary\Enums\AuthAttemptStatusEnum;
use JR\ChefsDiary\Exceptions\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use JR\ChefsDiary\DataObjects\Data\RegisterUserData;
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
                $data['email'],
                $data['password']
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
        echo 'login';
        $data = $this->requestValidatorFactory->make(UserLoginRequestValidator::class)->validate(
            $request->getParsedBody()
        );

        $status = $this->authService->attemptLogin($data);

        if ($status === AuthAttemptStatusEnum::FAILED) {
            throw new ValidationException(['unauthorized' => ['incorrectLoginPassword']], HttpStatusCode::UNAUTHORIZED->value);
        }

        if ($status === AuthAttemptStatusEnum::DISABLED) {
            throw new ValidationException(['forbidden' => ['accessDenied']], HttpStatusCode::FORBIDDEN->value);
        }

        // TODO: Dvoufazove overeni
        // if ($status === AuthAttemptStatus::TWO_FACTOR_AUTH) {
        //     return $this->responseFormatter->asJson($response, ['two_factor' => true]);
        // }

        return $this->responseFormatter->asJson($response, $status);
    }

    public function logout(Request $request, Response $response): Response
    {
        // $this->authService->logout();

        return $response->withStatus(HttpStatusCode::NO_CONTENT->value);
    }

    public function refreshToken(Request $request, Response $response): Response
    {

        $status = $this->authService->refreshToken();


        return $this->responseFormatter->asJson($response, $status);
    }
}