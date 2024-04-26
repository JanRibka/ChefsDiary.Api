<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\User\Implementation;

use DateTime;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;
use JR\ChefsDiary\Entity\User\Contract\UserLoginCodeInterface;

#[Entity, Table('UserLoginCodes')]
class UserLoginCode implements UserLoginCodeInterface
{
    #[Id]
    #[GeneratedValue(strategy: "AUTO")]
    #[Column(options: ['unsigned' => true])]
    private int $IdUserLoginCodes;

    #[Column(length: 6)]
    private string $Code;

    #[Column(options: ['default' => false])]
    private bool $IsUsed;

    #[Column]
    private DateTime $ExpireDate;

    #[ManyToOne(inversedBy: 'IdUser', targetEntity: User::class)]
    #[JoinColumn(name: 'IdUser', referencedColumnName: 'IdUser', options: ['unsigned' => true], nullable: false)]
    private User $User;

    public function __construct()
    {
        $this->IsUsed = false;
    }


    // Getters
    public function getId(): int
    {
        return $this->IdUserLoginCodes;
    }

    public function getCode(): string
    {
        return $this->Code;
    }

    public function getIsUsed(): bool
    {
        return $this->IsUsed;
    }

    public function getExpireDate(): DateTime
    {
        return $this->ExpireDate;
    }

    public function getUser(): User
    {
        return $this->User;
    }


    // Setters
    public function setCode(string $code): UserLoginCode
    {
        $this->Code = $code;

        return $this;
    }

    public function setIsUsed(bool $isUsed): UserLoginCode
    {
        $this->IsUsed = $isUsed;

        return $this;
    }

    public function setExpireDate(DateTime $expireDate): UserLoginCode
    {
        $this->ExpireDate = $expireDate;

        return $this;
    }

    public function setUser(UserInterface $user): UserLoginCode
    {
        $this->User = $user;

        return $this;
    }
}