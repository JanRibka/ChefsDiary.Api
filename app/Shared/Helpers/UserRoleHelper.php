<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Shared\Helpers;

use JR\ChefsDiary\Entity\User\Contract\UserRolesInterface;

class UserRoleHelper
{

    public static function getRoleValueArrayFromUserRoles(array $userRoles)
    {
        $getRoles = self::getRoles();

        return array_map($getRoles, $userRoles);
    }

    #region Private methods
    private static function getRoles()
    {
        return function (UserRolesInterface $userRole) {
            return $userRole->getUserRoleType()->getValue();
        };
    }
    #endregion
}