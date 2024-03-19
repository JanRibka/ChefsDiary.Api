<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Implementation;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use JR\ChefsDiary\Enums\HttpStatusCode;
use Psr\Http\Message\ResponseInterface;
use JR\ChefsDiary\DataObjects\TokenConfig;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;
use JR\ChefsDiary\Services\Contract\TokenServiceInterface;

class TokenService implements TokenServiceInterface
{
    public function __construct(
        private readonly TokenConfig $config
    ) {
    }


    public function createAccessToken(UserInterface $user, array $roles): string
    {
        $payload = [
            'userInfo' => [
                'uuid' => $user->getUuid(),
                'roles' => $roles
            ],
            'exp' => $this->config->expAccess
        ];

        return JWT::encode(
            $payload,
            $this->config->keyAccess,
            $this->config->algorithm
        );
    }

    public function createRefreshToken(UserInterface $user): string
    {
        $payload = [
            'uuid' => $user->getUuid(),
            'exp' => $this->config->expRefresh,
        ];

        return JWT::encode(
            $payload,
            $this->config->keyRefresh,
            $this->config->algorithm
        );
    }

    public function verifyJWT(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authHeader = $request->getHeaderLine('HTTP_AUTHORIZATION');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return $handler->handle($request)->withStatus(HttpStatusCode::UNAUTHORIZED->value);
        }

        $token = explode(' ', $authHeader)[1];

        try {
            $key = new Key($this->config->keyAccess, $this->config->algorithm);
            $decoded = JWT::decode($token, $key);

            $request = $request->withAttribute('uuid', $decoded->userInfo->uuid);
            $request = $request->withAttribute('roles', $decoded->userInfo->roles);

            return $handler->handle($request);
        } catch (Exception) {
            return $handler->handle($request)->withStatus(HttpStatusCode::FORBIDDEN->value);
        }

    }
}