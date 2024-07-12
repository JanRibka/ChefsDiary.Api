<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Implementation;

use Doctrine\ORM\Tools\Pagination\Paginator;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;
use JR\ChefsDiary\DataObjects\Data\DataTableQueryParams;
use JR\ChefsDiary\Entity\User\Contract\UserInfoInterface;
use JR\ChefsDiary\Services\Contract\UserServiceInterface;
use JR\ChefsDiary\Repositories\Contract\UserRepositoryInterface;

class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function getByUuid(string $uuid): ?UserInterface
    {
        return $this->userRepository->getByUuid($uuid);
    }

    public function getPaginatedUsers(DataTableQueryParams $params): Paginator
    {
        $query = $this->userRepository->getPaginatedUsersQuery($params);
        $orderDir = strtolower($params->orderDir) === 'asc' ? 'asc' : 'desc';
        $orderBy = in_array($params->orderBy, ['Uuid', 'Login', 'IsDisabled']) ? 'u' . $params->orderBy : '';

        if (empty($orderBy)) {
            $orderBy = in_array($params->orderBy, ['Email', 'CreatedA']) ? 'ui' . $params->orderBy : 'ui.Email';
        }
        // Kvůli orderBY tu asi bude muset být query a UserResponseData
        if (!empty($params->searchTerm)) {
            $query->where('u.Login LIKE :login')->setParameter(
                'login',
                '%' . addcslashes($params->searchTerm, '%_') . '%'
            );
        }

        $query->orderBy($orderBy, $orderDir);

        return new Paginator($query);
    }

    public function getUserForEdit(string $uuid): UserInfoInterface
    {
        return $this->userRepository->getUserForEdit($uuid);
    }

}
