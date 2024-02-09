<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Repositories\Implementation;

use JR\ChefsDiary\DataObjects\RegisterUserData;
use JR\ChefsDiary\Entity\User\Implementation\User;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;
use JR\ChefsDiary\Services\Implementation\HashService;
use JR\ChefsDiary\Repositories\Contract\UserRepositoryInterface;
use JR\ChefsDiary\Services\Contract\EntityManagerServiceInterface;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerServiceInterface $entityManagerService,
        private readonly HashService $hashService
    ) {

    }

    public function getById(int $userId): ?UserInterface
    {
        return $this->entityManagerService->find(User::class, $userId);
    }

    public function createUser(RegisterUserData $data): UserInterface
    {
        $user = new User();

        $user->setLogin($data->login);
        $user->setPassword($this->hashService->hashPassword($data->password));

        $this->entityManagerService->sync($user);

        return $user;
    }
}