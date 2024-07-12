<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Contract;

use Doctrine\ORM\Tools\Pagination\Paginator;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;
use JR\ChefsDiary\DataObjects\Data\DataTableQueryParams;
use JR\ChefsDiary\Entity\User\Contract\UserInfoInterface;

interface UserServiceInterface
{
    public function getByUuid(string $uuid): ?UserInterface;
    public function getPaginatedUsers(DataTableQueryParams $params): Paginator;
    public function getUserForEdit(string $uuid): UserInfoInterface;
}