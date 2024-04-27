<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Controllers;

use JR\ChefsDiary\Enums\HttpStatusCode;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use JR\ChefsDiary\Entity\User\Implementation\User;
use JR\ChefsDiary\Services\Contract\VerifyServiceInterface;

class VerifyUserController
{
    public function __construct(
        private readonly VerifyServiceInterface $verifyService
    ) {

    }
    public function verify(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        /** @var User $user */
        $user = $request->getAttribute('user');

        $this->verifyService->verify($user, $args);

        return $response->withStatus(HttpStatusCode::FOUND->value);
    }

    public function resend(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /** @var User $user */
        $user = $request->getAttribute('user');

        $this->verifyService->resend($user);

        return $response;
    }
}