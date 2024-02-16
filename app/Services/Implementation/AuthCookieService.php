<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Implementation;

use JR\ChefsDiary\DataObjects\AuthCookieConfig;
use JR\ChefsDiary\Services\Contract\AuthCookieServiceInterface;

class AuthCookieService implements AuthCookieServiceInterface
{
    public function __construct(
        private readonly AuthCookieConfig $config
    ) {
    }


    function setCookie(string $value, bool $session = false): void
    {
        setcookie($this->config->name, '', [
            'expires' => $session ? "Session" : $this->config->expires,
            'path' => $this->config->path,
            'httpOnly' => $this->config->httpOnly,
            'secure' => $this->config->secure,
            'sameSite' => $this->config->sameSite,
        ]);
    }

    public function getCookie(string $name): string
    {
        return $_COOKIE[$name];
    }

    function deleteCookie(string $value): void
    {
        setcookie('jwt', $value, [
            'expires' => time(),
            'path' => $this->config->path,
            'httpOnly' => $this->config->httpOnly,
            'secure' => $this->config->secure,
            'sameSite' => $this->config->sameSite,
        ]);
    }
}