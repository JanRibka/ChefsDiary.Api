<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Implementation;

use JR\ChefsDiary\Shared\UserInterface;
use JR\ChefsDiary\DataObjects\RegisterUserData;
use JR\ChefsDiary\Services\Contracts\AuthInterface;
use JR\ChefsDiary\Services\Contract\AuthServiceInterface;
use JR\ChefsDiary\Repositories\Contract\UserRepositoryInterface;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function register(RegisterUserData $data): UserInterface
    {
        $user = $this->userRepository->createUser($data);

        return $user;
    }
}