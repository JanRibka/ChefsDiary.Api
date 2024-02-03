<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Shared\Interfaces;

interface UserInterface
{
    public function getId(): int;
    public function getLogin(): string;
}