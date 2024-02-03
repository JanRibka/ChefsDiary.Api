<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Shared;

interface UserInterface
{
    public function getId(): int;
    public function getPassword(): string;
}