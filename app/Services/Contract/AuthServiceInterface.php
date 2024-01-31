<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Contract;

use JR\ChefsDiary\Shared\UserInterface;
use JR\ChefsDiary\DataObjects\RegisterUserData;

interface AuthServiceInterface
{
    public function register(RegisterUserData $data): UserInterface;
}