<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Shared\Interfaces;

interface OwnableInterface
{
    public function getUser(): UserInterface;
}