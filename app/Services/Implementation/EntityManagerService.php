<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Implementation;

use Doctrine\ORM\EntityManagerInterface;
use JR\ChefsDiary\Services\Contracts\EntityManagerServiceInterface;

class EntityManagerService implements EntityManagerServiceInterface
{
    public function __construct(

        protected readonly EntityManagerInterface $iEntityManagerService
    ) {
    }

    public function __call(string $name, array $arguments)
    {
        if (method_exists($this->iEntityManagerService, $name)) {
            return call_user_func_array([$this->iEntityManagerService, $name], $arguments);
        }

    }
}