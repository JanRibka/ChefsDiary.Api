<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\User\Contract;

interface UserRolesInterface
{
    public function getId(): int;

    /**
     * Summary of getUserRoleType
     * @return \JR\ChefsDiary\Entity\User\Contract\UserRoleTypeInterface[]
     * @author Jan Ribka
     */
    public function getUserRoleTypes(): array;
}