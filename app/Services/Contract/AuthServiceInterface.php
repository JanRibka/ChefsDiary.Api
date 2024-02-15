<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Contract;


use JR\ChefsDiary\Enums\AuthAttemptStatusEnum;
use JR\ChefsDiary\DataObjects\RegisterUserData;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;

interface AuthServiceInterface
{
    // public function getUser(): ?UserInterface;
    public function register(RegisterUserData $data): UserInterface;

    /**
     * Přihlášení
     * @param string[] $credentials
     * @return \JR\ChefsDiary\Enums\AuthAttemptStatusEnum
     * @author Jan Ribka
     */
    public function attemptLogin(array $credentials): AuthAttemptStatusEnum;
}