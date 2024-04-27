<?php

declare(strict_types=1);

namespace App\Services\Contract;

use JR\ChefsDiary\Entity\User\Contract\UserInterface;
use App\Entity\User\Contract\UserPasswordResetInterface;

interface PasswordResetServiceInterface
{
    public function generate(string $email): UserPasswordResetInterface;
    public function deactivateAllPasswordResets(string $email): void;
    public function findByToken(string $token): UserPasswordResetInterface|null;
    public function updatePassword(UserInterface $user, string $password): void;
    public function getUserByEmail(string $email): UserInterface|null;
}