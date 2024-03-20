<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Contract;

use JR\ChefsDiary\Enums\AuthAttemptStatusEnum;
use JR\ChefsDiary\DataObjects\Data\RegisterUserData;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;

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

    public function logout(UserInterface $user): void;

    public function refreshToken(): string;
}