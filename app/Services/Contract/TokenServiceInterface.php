<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Contract;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;

interface TokenServiceInterface
{
    public function createAccessToken(UserInterface $user, array $role): string;

    public function createRefreshToken(UserInterface $user): string;

    public function verifyJWT(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;
}

