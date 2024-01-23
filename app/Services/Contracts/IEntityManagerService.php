<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Contracts;

interface IEntityManagerService
{
    public function __call(string $name, array $arguments);
}

