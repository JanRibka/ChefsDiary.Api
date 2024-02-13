<?php

declare(strict_types=1);

namespace JR\ChefsDiary\DataObjects;

class AuthCookieConfig
{
    public function __construct(
        public readonly string $name,
        public readonly bool $secure,
        public readonly bool $httpOnly,
        public readonly string $sameSite,
        public readonly int $expires,
        public readonly string $path,
    ) {
    }
}