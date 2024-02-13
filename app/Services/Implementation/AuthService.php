<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Implementation;

use JR\ChefsDiary\Enums\AuthAttemptStatusEnum;
use JR\ChefsDiary\DataObjects\RegisterUserData;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;
use JR\ChefsDiary\Services\Contract\AuthServiceInterface;
use JR\ChefsDiary\Services\Contract\TokenServiceInterface;
use JR\ChefsDiary\Services\Contract\AuthCookieServiceInterface;
use JR\ChefsDiary\Repositories\Contract\UserRepositoryInterface;

class AuthService implements AuthServiceInterface
{

    private ?UserInterface $user = null;

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly TokenServiceInterface $tokenService,
        private readonly AuthCookieServiceInterface $authCookieService
    ) {
    }

    public function register(RegisterUserData $data): UserInterface
    {
        $user = $this->userRepository->createUser($data);

        return $user;
    }

    public function attemptLogin(array $credentials): AuthAttemptStatusEnum
    {
        $login = $credentials['login'];
        $password = $credentials['password'];
        $user = $this->userRepository->getByLogin($login);

        if (!$user) {
            return AuthAttemptStatusEnum::FAILED;
        }

        if (!$this->checkCredentials($user, $password)) {
            $this->userRepository->logLoginAttempt($user, false);

            return AuthAttemptStatusEnum::FAILED;
        }

        // TODO: Dvoufazove overeni
        // if ($user->hasTwoFactorAuthEnabled()) {
        //     $this->startLoginWith2FA($user);

        //     return AuthAttemptStatus::TWO_FACTOR_AUTH;
        // }

        $this->login($user);
        $this->userRepository->logLoginAttempt($user, true);

        return AuthAttemptStatusEnum::SUCCESS;
    }

    private function checkCredentials(UserInterface $user, string $password): bool
    {
        return password_verify($password, $user->getPassword());
    }

    private function login(UserInterface $user): void
    {
        $accessToken = $this->tokenService->createAccessToken($user, ["sd"]);

        $refreshToken = $this->tokenService->createRefreshToken($user);

        $this->authCookieService->setCookie($refreshToken);

        $this->user = $user;
    }
}