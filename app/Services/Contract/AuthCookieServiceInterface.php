<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Contract;

interface AuthCookieServiceInterface
{
    public function setCookie(string $value, bool $session = false): void;

    public function deleteCookie(string $value): void;

}