<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Contract;

use JR\ChefsDiary\DataObjects\Data\CookieConfigData;

interface CookieServiceInterface
{
    public function start(): void;

    public function set(string $key, string $value, CookieConfigData|null $config = null): void;

    public function get(string $key): string|null;

    public function delete(string $key, CookieConfigData|null $config = null): void;

    public function exists(string $key): bool;

}