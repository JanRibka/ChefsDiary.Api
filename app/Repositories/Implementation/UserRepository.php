<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Repositories\Implementation;

use JR\ChefsDiary\Entity\User;
use JR\ChefsDiary\Shared\UserInterface;
use JR\ChefsDiary\DataObjects\RegisterUserData;
use JR\ChefsDiary\Repositories\Contract\UserRepositoryInterface;
use JR\ChefsDiary\Services\Contract\EntityManagerServiceInterface;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerServiceInterface $entityManagerService
    ) {

    }

    public function getById(int $userId): ?UserInterface
    {
        return $this->entityManagerService->find(User::class, $userId);
    }

    public function createUser(RegisterUserData $data): UserInterface
    {
        $user = new User();

        return $user;
    }
}