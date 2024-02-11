<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\User\Contract;

interface UserInterface
{
    public function getId(): int;
    public function getLogin(): string;
    public function getPassword(): string;
}