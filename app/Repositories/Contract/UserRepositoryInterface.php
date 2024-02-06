<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Repositories\Contract;

use JR\ChefsDiary\DataObjects\RegisterUserData;
use JR\ChefsDiary\Shared\Interfaces\UserInterface;

interface UserRepositoryInterface
{
    public function getById(int $userId): ?UserInterface;

    public function createUser(RegisterUserData $data): UserInterface;
}