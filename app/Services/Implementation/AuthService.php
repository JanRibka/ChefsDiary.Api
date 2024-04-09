<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Implementation;

use JR\ChefsDiary\Enums\DomainEnum;
use JR\ChefsDiary\DataObjects\Data\UserData;
use JR\ChefsDiary\Enums\AuthAttemptStatusEnum;
use JR\ChefsDiary\Enums\LogoutAttemptStatusEnum;
use JR\ChefsDiary\Shared\Helpers\UserRoleHelper;
use JR\ChefsDiary\DataObjects\Data\UserTokenData;
use JR\ChefsDiary\DataObjects\Configs\TokenConfig;
use JR\ChefsDiary\DataObjects\Data\CookieConfigData;
use JR\ChefsDiary\DataObjects\Data\RegisterUserData;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;
use JR\ChefsDiary\Enums\RefreshTokenAttemptStatusEnum;
use JR\ChefsDiary\DataObjects\Configs\AuthCookieConfig;
use JR\ChefsDiary\Services\Contract\AuthServiceInterface;
use JR\ChefsDiary\Services\Contract\TokenServiceInterface;
use JR\ChefsDiary\Services\Contract\CookieServiceInterface;
use JR\ChefsDiary\Repositories\Contract\UserRepositoryInterface;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly TokenServiceInterface $tokenService,
        private readonly CookieServiceInterface $cookieService,
        private readonly AuthCookieConfig $authCookieConfig,
        private readonly TokenConfig $tokenConfig
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
        $persistLogin = (bool) ($credentials['persistLogin'] ?? false);
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

        return $this->login($user, $persistLogin);

    }

    public function attemptLogout(): LogoutAttemptStatusEnum
    {
        $refreshToken = $this->cookieService->get($this->authCookieConfig->name);

        if (!$refreshToken) {
            return LogoutAttemptStatusEnum::NO_COOKIE;
        }

        $user = $this->userRepository->getByRefreshToken($refreshToken);

        if (!$user) {
            $this->cookieService->delete($this->authCookieConfig->name);

            return LogoutAttemptStatusEnum::NO_USER;
        }

        return $this->logout($user);
    }

    public function attemptRefreshToken(): RefreshTokenAttemptStatusEnum|array
    {
        $refreshToken = $this->cookieService->get($this->authCookieConfig->name);

        if (!$refreshToken) {
            return RefreshTokenAttemptStatusEnum::NO_COOKIE;
        }

        $this->cookieService->delete($this->authCookieConfig->name);
        $user = $this->userRepository->getByRefreshToken($refreshToken);

        // Detected refresh token reuse!
        if (!$user) {
            $decoded = $this->tokenService->decodeToken($refreshToken, $this->tokenConfig->keyRefresh);

            if (!$decoded) {
                return RefreshTokenAttemptStatusEnum::NO_USER;
            }

            $hackedLogin = $decoded->login;
            $hackedUser = $this->userRepository->getByLogin($hackedLogin);

            $this->userRepository->createUpdateRefreshToken($hackedUser, null, DomainEnum::WEB);

            return RefreshTokenAttemptStatusEnum::NO_USER;
        }

        return $this->refreshToken($user, $refreshToken);
    }

    #region Private methods
    private function checkCredentials(UserInterface $user, string $password): bool
    {
        return password_verify($password, $user->getPassword());
    }

    private function login(UserInterface $user, bool $persistLogin): array
    {
        $userRoles = $this->userRepository->getUserRolesByUserId($user->getId());
        $roleValueArray = UserRoleHelper::getRoleValueArrayFromUserRoles($userRoles);
        $refreshToken = $this->tokenService->createRefreshToken($user);

        $this->userRepository->createUpdateRefreshToken($user, $refreshToken, DomainEnum::WEB);
        $this->userRepository->logLoginAttempt($user, true);

        $config = new CookieConfigData(
            $this->authCookieConfig->secure,
            $this->authCookieConfig->httpOnly,
            $this->authCookieConfig->sameSite,
            $persistLogin ? $this->authCookieConfig->expires : "session",
            $this->authCookieConfig->path
        );

        $this->cookieService->set(
            $this->authCookieConfig->name,
            $refreshToken,
            $config
        );

        $accessToken = $this->tokenService->createAccessToken($user, $roleValueArray);

        return [
            'login' => $user->getLogin(),
            'accessToken' => $accessToken
        ];
    }

    private function logout(UserInterface $user): LogoutAttemptStatusEnum
    {
        $this->userRepository->createUpdateRefreshToken($user, null, DomainEnum::WEB);

        $config = new CookieConfigData(
            $this->authCookieConfig->secure,
            $this->authCookieConfig->httpOnly,
            $this->authCookieConfig->sameSite,
            $this->authCookieConfig->expires,
            $this->authCookieConfig->path
        );

        $this->cookieService->delete($this->authCookieConfig->name, $config);

        return LogoutAttemptStatusEnum::LOGOUT_SUCCESS;
    }

    private function refreshToken(UserInterface $user, string $refreshToken): RefreshTokenAttemptStatusEnum|array
    {
        $decoded = $this->tokenService->decodeToken($refreshToken, $this->tokenConfig->keyRefresh);

        if ($user->getUuid() !== $decoded->uuid) {
            return RefreshTokenAttemptStatusEnum::USER_NOT_EQUAL;
        }

        $userRoles = $this->userRepository->getUserRolesByUserId($user->getId());
        $roleValueArray = UserRoleHelper::getRoleValueArrayFromUserRoles($userRoles);
        $accessToken = $this->tokenService->createAccessToken($user, $roleValueArray);

        return [
            'login' => $user->getLogin(),
            'accessToken' => $accessToken
        ];
    }
    #region
}
