<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Implementation;

use JR\ChefsDiary\DataObjects\UserData;
use JR\ChefsDiary\Enums\AuthAttemptStatusEnum;
use JR\ChefsDiary\DataObjects\RegisterUserData;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;
use JR\ChefsDiary\Services\Contract\AuthServiceInterface;
use JR\ChefsDiary\Entity\User\Contract\UserRolesInterface;
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

    /**
     * Přihlášení
     * @param string[] $credentials
     * @return \JR\ChefsDiary\Enums\AuthAttemptStatusEnum
     * @author Jan Ribka
     */
    public function attemptLogin(array $credentials): AuthAttemptStatusEnum
    {
        $login = $credentials['login'];
        $password = $credentials['password'];
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

        $accessToken = $this->login($user);
        $this->userRepository->logLoginAttempt($user, true);

        return AuthAttemptStatusEnum::SUCCESS;
    }

    private function checkCredentials(UserInterface $user, string $password): bool
    {
        return password_verify($password, $user->getPassword());
    }

    private function login(UserInterface $user): string
    {
        $getRoles = function (UserRolesInterface $userRole) {
            return $userRole->getUserRoleType()->getValue();
        };

        $userRoles = $this->userRepository->getUserRolesByUserId($user->getId());
        $rolesArray = array_map($getRoles, $userRoles);
        $refreshToken = $this->tokenService->createRefreshToken($user);

        $this->userRepository->update(
            $user,
            new UserData(
                $refreshToken
            )
        );

        $this->authCookieService->setCookie($refreshToken);
        $this->user = $user;

        return $this->tokenService->createAccessToken($user, $rolesArray);
    }
}