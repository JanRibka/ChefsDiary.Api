<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Implementation;

class HashService
{
    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
}