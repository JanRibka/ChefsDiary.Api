<?php

declare(strict_types=1);

namespace JR\ChefsDiary\DataObjects\Configs;

use JR\ChefsDiary\Enums\SameSiteEnum;

class AuthCookieConfig
{
    public function __construct(
        public readonly string $name,
        public readonly bool $secure,
        public readonly bool $httpOnly,
        public readonly SameSiteEnum $sameSite,
        public readonly int $expires,
        public readonly string $path,
    ) {
    }
}