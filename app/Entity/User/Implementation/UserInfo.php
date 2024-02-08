<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\User\Implementation;

use DateTime;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use JR\ChefsDiary\Entity\Traits\HasTimestamp;
use JR\ChefsDiary\Entity\User\Contract\UserInfoInterface;

#[Entity, Table('UserInfo')]
class UserInfo implements UserInfoInterface
{
    use HasTimestamp;

    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $IdUserInfo;

    #[OneToOne(mappedBy: 'IdUser', targetEntity: User::class)]
    #[JoinColumn(name: 'IdUser', referencedColumnName: 'IdUser', options: ['unsigned' => true], nullable: false)]
    private int $IdUser;

    #[Column(length: 50, nullable: true)]
    private string|null $UserName;

    #[Column(length: 50, nullable: true)]
    private string|null $Email;

    #[Column(length: 50, nullable: true)]
    private string|null $Phone;


    // Getters
    public function getId(): int
    {
        return $this->IdUserInfo;
    }

    public function getIdUser(): int
    {
        return $this->IdUser;
    }

    public function getUserName(): string|null
    {
        return $this->UserName;
    }

    public function getUserEmail(): string|null
    {
        return $this->Email;
    }

    public function getUserPhone(): string|null
    {
        return $this->Phone;
    }


    // Setters
    public function setIdUser(int $idUser): UserInfo
    {
        $this->IdUser = $idUser;

        return $this;
    }

    public function setUserName(string $userName): UserInfo
    {
        $this->UserName = $userName;

        return $this;
    }

    public function setEmail(string $email): UserInfo
    {
        $this->Email = $email;

        return $this;
    }

    public function setPhone(string $phone): UserInfo
    {
        $this->Phone = $phone;

        return $this;
    }
}