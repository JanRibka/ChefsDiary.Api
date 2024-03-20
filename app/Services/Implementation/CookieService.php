<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Implementation;

use JR\ChefsDiary\DataObjects\Data\CookieConfigData;
use JR\ChefsDiary\Services\Contract\CookieServiceInterface;

class AuthCookieService implements CookieServiceInterface
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
            'sameSite' => $config?->sameSite,
        ]);
    }

    public function get(string $key): string
    {
        return $_COOKIE[$key];
    }

    public function delete(string $key): void
    {
        // setcookie('jwt', $value, [
        //     'expires' => time(),
        //     'path' => $this->config->path,
        //     'httpOnly' => $this->config->httpOnly,
        //     'secure' => $this->config->secure,
        //     'sameSite' => $this->config->sameSite,
        // ]);

        unset($_COOKIE[$key]);
    }

    public function exists(string $key): bool
    {
        return isset ($_COOKIE[$key]);
    }
}