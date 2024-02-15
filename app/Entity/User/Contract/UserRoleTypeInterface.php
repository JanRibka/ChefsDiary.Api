<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\User\Contract;

interface UserRoleTypeInterface
{
    public function getId(): int;
    public function getValue(): int;
}