<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Shared;

interface OwnableInterface
{
    public function getUser(): UserInterface;
}