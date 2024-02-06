<?php

declare(strict_types=1);

namespace JR\ChefsDiary\RequestValidators;

use JR\ChefsDiary\RequestValidators\Auth\RequestValidatorInterface;

interface RequestValidatorFactoryInterface
{
    public function make(string $class): RequestValidatorInterface;
}