<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Repositories\Contract;

use JR\ChefsDiary\DataObjects\RegisterUserData;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;

interface UserRepositoryInterface
{
    public function getById(int $userId): ?UserInterface;

    public function getByLogin(string $login): ?UserInterface;

    public function createUser(RegisterUserData $data): UserInterface;

    public function logLoginAttempt(UserInterface $user, bool $successful): void;
}