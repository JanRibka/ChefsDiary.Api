<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\User\Implementation;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\GeneratedValue;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;
use JR\ChefsDiary\Entity\User\Contract\UserRolesInterface;
use JR\ChefsDiary\Entity\User\Contract\UserRoleTypeInterface;

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


    #[ManyToMany(inversedBy: 'IdUserRoleType', targetEntity: UserRoleType::class)]
    #[JoinColumn(name: 'IdUserRoleType', referencedColumnName: 'IdUserRoleType', options: ['unsigned' => true], nullable: false)]
    private array $UserRoleTypes;


    // Getters
    public function getId(): int
    {
        return $this->IdUserRole;
    }

    public function getUser(): User
    {
        return $this->User;
    }

    /**
     * getUserRoleTypes
     * @return \JR\ChefsDiary\Entity\User\Implementation\UserRoleType[]
     * @author Jan Ribka
     */
    public function getUserRoleTypes(): array
    {
        return $this->UserRoleTypes;
    }


    // Setters
    public function setUser(UserInterface $user): UserRoles
    {
        $this->User = $user;

        return $this;
    }

    /**
     * Summary of setUserRoleType
     * @param \JR\ChefsDiary\Entity\User\Contract\UserRoleTypeInterface[]
     * @return \JR\ChefsDiary\Entity\User\Implementation\UserRoles
     * @author Jan Ribka
     */
    public function setUserRoleTypes(array $userRoleTypes): UserRoles
    {
        $this->UserRoleTypes = $userRoleTypes;

        return $this;
    }
}