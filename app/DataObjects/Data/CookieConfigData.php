<?php

declare(strict_types=1);

namespace JR\ChefsDiary\DataObjects\Data;

class CookieConfigData
{
    public function __construct(
        public readonly bool $secure,
        public readonly bool $httpOnly,
        public readonly string $sameSite,
        public readonly int|string $expires,
        public readonly string $path,
    ) {
    }
}