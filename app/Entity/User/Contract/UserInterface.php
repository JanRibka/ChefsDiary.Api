<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\User\Contract;

use JR\ChefsDiary\Entity\User\Implementation\User;

interface UserInterface
{
    public function getId(): int;
    public function getUuid(): string;
    public function getLogin(): string;
    public function getPassword(): string;
    public function getIsDisabled(): bool;
    public function setPassword(string $password): User;
    public function getTwoFactor(): bool;
}