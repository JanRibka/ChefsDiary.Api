<?php

declare(strict_types=1);

namespace JR\ChefsDiary\DataObjects\Data;

class UserTokenData
{
    public function __construct(
        public readonly string|null $refreshToken
    ) {
    }
}