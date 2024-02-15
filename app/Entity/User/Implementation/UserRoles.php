<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\User\Implementation;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use JR\ChefsDiary\Entity\User\Contract\UserRolesInterface;

#[Entity, Table('UserRoles')]
class UserRoles implements UserRolesInterface
{
    #[Id]
    #[GeneratedValue(strategy: "AUTO")]
    #[Column(options: ['unsigned' => true])]
    private int $IdUserRole;

    #[ManyToOne(inversedBy: 'IdUser', targetEntity: User::class)]
    #[JoinColumn(name: 'IdUser', referencedColumnName: 'IdUser', options: ['unsigned' => true], nullable: false)]
    private User $User;

    #[ManyToOne(inversedBy: 'IdUserRoleType', targetEntity: UserRoleType::class)]
    #[JoinColumn(name: 'IdUserRoleType', referencedColumnName: 'IdUserRoleType', options: ['unsigned' => true], nullable: false)]
    private UserRoleType $UserRoleType;


    // Getters
    public function getId(): int
    {
        return $this->IdUserRole;
    }

    public function getUser(): User
    {
        return $this->User;
    }

    public function getUserRoleType(): UserRoleType
    {
        return $this->UserRoleType;
    }


    // Setters
    public function setUser(User $user): UserRoles
    {
        $this->User = $user;

        return $this;
    }

    public function setUserRoleType(UserRoleType $userRoleType): UserRoles
    {
        $this->UserRoleType = $userRoleType;

        return $this;
    }
}