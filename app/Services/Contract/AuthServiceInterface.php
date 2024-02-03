<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Contract;


use JR\ChefsDiary\DataObjects\RegisterUserData;
use JR\ChefsDiary\Shared\Interfaces\UserInterface;

interface AuthServiceInterface
{
    public function getUser(): ?UserInterface;
    public function register(RegisterUserData $data): UserInterface;
}