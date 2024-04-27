<?php

declare(strict_types=1);

namespace App\Controllers;

use JR\ChefsDiary\Enums\HttpStatusCode;
use JR\ChefsDiary\Mail\ForgotPasswordEmail;
use JR\ChefsDiary\Exceptions\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\Contract\PasswordResetServiceInterface;
use JR\ChefsDiary\RequestValidators\RequestValidatorFactoryInterface;
use JR\ChefsDiary\RequestValidators\PasswordReset\ResetPasswordRequestValidator;
use JR\ChefsDiary\RequestValidators\PasswordReset\ForgotPasswordRequestValidator;

class PasswordResetController
{
    public function __construct(
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
        private readonly PasswordResetServiceInterface $passwordResetService,
        private readonly ForgotPasswordEmail $forgotPasswordEmail
    ) {
    }

    public function forgotPasswordRequest(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory->make(ForgotPasswordRequestValidator::class)->validate(
            $request->getParsedBody()
        );

        $user = $this->passwordResetService->getUserByEmail($data['email']);

        if ($user) {
            $this->passwordResetService->generate($data['email']);

            $passwordReset = $this->passwordResetService->generate($data['email']);

            $this->forgotPasswordEmail->send($passwordReset);
        }

        return $response;
    }

    public function resetPassword(Request $request, Response $response, array $args): Response
    {
        $data = $this->requestValidatorFactory->make(ResetPasswordRequestValidator::class)->validate(
            $request->getParsedBody()
        );

        $passwordReset = $this->passwordResetService->findByToken($args['token']);

        if (!$passwordReset) {
            throw new ValidationException(['confirmPassword' => ['tokenInvalid']], HttpStatusCode::BAD_REQUEST->value);
        }

        $user = $this->passwordResetService->getUserByEmail($passwordReset->getEmail());

        if (!$user) {
            throw new ValidationException(['confirmPassword' => ['emailInvalid']], HttpStatusCode::BAD_REQUEST->value);
        }

        $this->passwordResetService->updatePassword($user, $data['password']);

        return $response;
    }
}