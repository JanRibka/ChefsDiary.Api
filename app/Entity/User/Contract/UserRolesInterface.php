<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\User\Contract;

use JR\ChefsDiary\Entity\User\Implementation\UserRoleType;

interface UserRolesInterface
{
    public function getId(): int;
    public function getUserRoleType(): UserRoleType;
}