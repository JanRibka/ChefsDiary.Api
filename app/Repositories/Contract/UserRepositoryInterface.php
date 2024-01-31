<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Repositories\Contract;

use JR\ChefsDiary\Shared\UserInterface;
use JR\ChefsDiary\DataObjects\RegisterUserData;

interface UserRepositoryInterface
{
    public function getById(int $userId): ?UserInterface;

    public function createUser(RegisterUserData $data): UserInterface;
}