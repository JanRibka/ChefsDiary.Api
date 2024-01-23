<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Implementation;

use JR\ChefsDiary\Services\Contracts\IEntityManagerService;

class EntityManagerService implements IEntityManagerService
{
    public function __construct(protected readonly IEntityManagerService $iEntityManagerService)
    {
    }

    public function __call(string $name, array $arguments)
    {
        if (method_exists($this->iEntityManagerService, $name)) {
            return call_user_func_array([$this->iEntityManagerService, $name], $arguments);
        }

    }
}