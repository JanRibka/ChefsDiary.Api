<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\User\Implementation;

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

    #[Id]
    #[GeneratedValue(strategy: "AUTO")]
    #[Column(options: ['unsigned' => true], nullable: false)]
    private int $IdUserInfo;

    #[Column(length: 50, nullable: true)]
    private string|null $UserName;

    #[Column(length: 50, nullable: true)]
    private string|null $Email;

    #[Column(length: 50, nullable: true)]
    private string|null $Phone;

    #[OneToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'IdUser', referencedColumnName: 'IdUser', nullable: false)]
    private User $User;


    // Getters
    public function getId(): int
    {
        return $this->IdUserInfo;
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

    public function setUser(User $user): UserInfo
    {
        $this->User = $user;

        return $this;
    }
}