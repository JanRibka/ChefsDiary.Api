<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Contract;

use Doctrine\ORM\Tools\Pagination\Paginator;
use JR\ChefsDiary\DataObjects\Data\DataTableQueryParams;

interface UserServiceInterface
{
    public function getPaginatedUsers(DataTableQueryParams $params): Paginator;
}