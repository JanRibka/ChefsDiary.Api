<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Contract;

use JR\ChefsDiary\Enums\AuthAttemptStatusEnum;
use JR\ChefsDiary\Enums\LogoutAttemptStatusEnum;
use JR\ChefsDiary\DataObjects\Data\RegisterUserData;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;
use JR\ChefsDiary\Enums\RefreshTokenAttemptStatusEnum;

interface AuthServiceInterface
{
    public function register(RegisterUserData $data): UserInterface;

    /**
     * Attempt to login user
     * @param string[] $credentials
     * @return \JR\ChefsDiary\Enums\AuthAttemptStatusEnum|array
     * @author Jan Ribka
     */
    public function attemptLogin(array $credentials): AuthAttemptStatusEnum|array;

    public function attemptLogout(): LogoutAttemptStatusEnum;

    public function attemptRefreshToken(): RefreshTokenAttemptStatusEnum|array;
}