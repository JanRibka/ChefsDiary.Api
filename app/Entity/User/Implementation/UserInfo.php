<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\User\Implementation;

use DateTime;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use JR\ChefsDiary\Entity\Traits\HasTimestamp;

use JR\ChefsDiary\Entity\User\Contract\UserInfoInterface;

#[Entity, Table('UserInfo')]
class UserInfo implements UserInfoInterface
{
    use HasTimestamp;

    #[Id]
    #[Column]
    private int $IdUserInfo;

    #[Column]
    private int $IdUser;

    #[Column]
    private string|null $UserName;

    #[Column]
    private string|null $Email;

    #[Column]
    private string|null $Phone;

    #[Column]
    private DateTime $CreatedAt;



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

    public function getUserCreatedAt(): DateTime
    {
        return $this->CreatedAt;
    }


    // Setters
    public function setUser(int $user): UserInfo
    {
        $this->IdUser = $user;

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

    public function setUserCreatedAt(DateTime $createdAt): UserInfo
    {
        $this->CreatedAt = $createdAt;

        return $this;
    }
}