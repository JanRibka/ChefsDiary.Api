<?php

declare(strict_types=1);

namespace JR\ChefsDiary\DataObjects;

class RegisterUserData
{
    public function __construct(
        public readonly string $email,
        public readonly string $password
    ) {
    }
}