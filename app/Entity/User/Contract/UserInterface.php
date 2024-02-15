<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\User\Contract;

interface UserInterface
{
    public function getId(): int;
    public function getUuid(): string;
    public function getLogin(): string;
    public function getPassword(): string;
    public function getIsDisabled(): bool;
}