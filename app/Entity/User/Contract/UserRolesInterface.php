<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\User\Contract;

interface UserRolesInterface
{
    public function getId(): int;
    public function getUserRoleType(): UserRoleTypeInterface;
}