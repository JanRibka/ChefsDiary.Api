<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Shared\Interfaces;

use JR\ChefsDiary\Entity\User\Contract\UserInterface;

interface OwnableInterface
{
    public function getUser(): UserInterface;
}