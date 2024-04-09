<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Implementation;

use JR\ChefsDiary\DataObjects\Data\CookieConfigData;
use JR\ChefsDiary\Services\Contract\CookieServiceInterface;

class CookieService implements CookieServiceInterface
{
    public function __construct()
    {
    }


    public function set(string $key, string $value, CookieConfigData|null $config = null): void
    {
        setcookie($key, $value, [
            'expires' => $config?->expires,
            'path' => $config?->path,
            'httpOnly' => $config?->httpOnly,
            'secure' => $config?->secure,
            'sameSite' => $config?->sameSite->value,
        ]);
    }

    public function get(string $key): string|null
    {
        return $_COOKIE[$key];
    }

    public function delete(string $key, CookieConfigData|null $config = null): void
    {
        setcookie($key, "", [
            'expires' => time(),
            'path' => $config->path,
            'httpOnly' => $config->httpOnly,
            'secure' => $config->secure,
            'sameSite' => $config->sameSite->value,
        ]);
    }

    public function exists(string $key): bool
    {
        return isset($_COOKIE[$key]);
    }
}