<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Shared\Interfaces;

// TODO: TOto tu asi nen9 potreba
interface OwnableInterface
{
    public function getUser(): UserInterface;
}