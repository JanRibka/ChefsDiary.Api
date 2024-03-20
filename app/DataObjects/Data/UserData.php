<?php

declare(strict_types=1);

namespace JR\ChefsDiary\DataObjects\Data;

class UserData
{
    public function __construct(
        public readonly string|null $refreshToken
    ) {
    }
}