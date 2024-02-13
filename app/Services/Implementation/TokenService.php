<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Implementation;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface;
use JR\ChefsDiary\DataObjects\TokenConfig;
use JR\ChefsDiary\Enums\HttpStatusCodeEnum;
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


    public function createAccessToken(UserInterface $user, array $role): string
    {
        $payload = [
            'userInfo' => [
                'login' => $user->getLogin(),
                'role' => $role
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
            'login' => $user->getLogin(),
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
            return $handler->handle($request)->withStatus(HttpStatusCodeEnum::UNAUTHORIZED->value);
        }

        $token = explode(' ', $authHeader)[1];

        try {
            $key = new Key($this->config->keyAccess, $this->config->algorithm);
            $decoded = JWT::decode($token, $key);

            $request = $request->withAttribute('login', $decoded->userInfo->login);
            $request = $request->withAttribute('role', $decoded->userInfo->role);

            return $handler->handle($request);
        } catch (Exception) {
            return $handler->handle($request)->withStatus(HttpStatusCodeEnum::FORBIDDEN->value);
        }

    }
}