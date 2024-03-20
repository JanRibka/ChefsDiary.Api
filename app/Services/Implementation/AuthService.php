<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Implementation;

use JR\ChefsDiary\DataObjects\Data\UserData;
use JR\ChefsDiary\Enums\AuthAttemptStatusEnum;
use JR\ChefsDiary\DataObjects\Data\CookieConfigData;
use JR\ChefsDiary\DataObjects\Data\RegisterUserData;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;
use JR\ChefsDiary\DataObjects\Configs\AuthCookieConfig;
use JR\ChefsDiary\Services\Contract\AuthServiceInterface;
use JR\ChefsDiary\Entity\User\Contract\UserRolesInterface;
use JR\ChefsDiary\Services\Contract\TokenServiceInterface;
use JR\ChefsDiary\Services\Contract\CookieServiceInterface;
use JR\ChefsDiary\Repositories\Contract\UserRepositoryInterface;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly TokenServiceInterface $tokenService,
        private readonly CookieServiceInterface $authCookieService,
        private readonly AuthCookieConfig $authCookieConfig
    ) {
    }

    /**
     * Register user
     * @param \JR\ChefsDiary\DataObjects\Data\RegisterUserData $data
     * @return \JR\ChefsDiary\Entity\User\Contract\UserInterface
     * @author Jan Ribka
     */
    public function register(RegisterUserData $data): UserInterface
    {
        $user = $this->userRepository->createUser($data);
        // TODO: Tady se bude pos9lat email s potvrzenim registrace

        return $user;
    }

    /**
     * Attempt to login
     * @param array $credentials
     * @return \JR\ChefsDiary\Enums\AuthAttemptStatusEnum|array
     * @author Jan Ribka
     */
    public function attemptLogin(array $credentials): AuthAttemptStatusEnum|array
    {
        $login = $credentials['login'];
        $password = $credentials['password'];
        // $persistLogin = $credentials['persistLogin'];
        $user = $this->userRepository->getByLogin($login);

        if (!$user) {
            return AuthAttemptStatusEnum::FAILED;
        }

        if ($user->getIsDisabled()) {
            return AuthAttemptStatusEnum::DISABLED;
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

        return $this->login($user);

    }

    public function logout(UserInterface $user): void
    {
    }

    public function refreshToken(): string
    {
        return '';
    }

    private function checkCredentials(UserInterface $user, string $password): bool
    {
        return password_verify($password, $user->getPassword());
    }

    private function login(UserInterface $user): array
    {
        $getRoles = function (UserRolesInterface $userRole) {
            return $userRole->getUserRoleType();
        };

        $userRoles = $this->userRepository->getUserRolesByUserId($user->getId());
        $roleArray = array_map($getRoles, $userRoles);
        $refreshToken = $this->tokenService->createRefreshToken($user);

        $this->userRepository->update(
            $user,
            new UserData(
                $refreshToken
            )
        );

        $this->userRepository->logLoginAttempt($user, true);

        $config = new CookieConfigData(
            $this->authCookieConfig->secure,
            $this->authCookieConfig->httpOnly,
            $this->authCookieConfig->sameSite,
            $this->authCookieConfig->expires,
            $this->authCookieConfig->path
        );

        $this->authCookieService->set(
            $this->authCookieConfig->name,
            $refreshToken,
            $config
        );

        $accessToken = $this->tokenService->createAccessToken($user, $roleArray);
        $data = [
            'uuid' => $user->getUuid(),
            'login' => $user->getLogin(),
            'userRoles' => $roleArray,
            'accessToken' => $accessToken
        ];

        return $data;
    }
}