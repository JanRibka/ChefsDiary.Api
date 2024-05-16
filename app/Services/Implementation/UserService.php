<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Implementation;

use Doctrine\ORM\Tools\Pagination\Paginator;
use JR\ChefsDiary\DataObjects\Data\DataTableQueryParams;
use JR\ChefsDiary\Services\Contract\UserServiceInterface;
use JR\ChefsDiary\Repositories\Contract\UserRepositoryInterface;

class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function getPaginatedUsers(DataTableQueryParams $params): Paginator
    {
        $query = $this->userRepository->getPaginatedUsersQuery($params);
        $orderBy = in_array($params->orderBy, ['Login']) ? $params->orderBy : 'Login';
        $orderDir = strtolower($params->orderDir) === 'asc' ? 'asc' : 'desc';

        if (!empty($params->searchTerm)) {
            $query->where('u.Login LIKE :login')->setParameter(
                'login',
                '%' . addcslashes($params->searchTerm, '%_') . '%'
            );
        }

        $query->orderBy('c.' . $orderBy, $orderDir);

        return new Paginator($query);
    }

}
