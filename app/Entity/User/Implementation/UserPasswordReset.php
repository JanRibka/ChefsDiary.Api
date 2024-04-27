<?php

declare(strict_types=1);

namespace App\Entity\User\Implementation;

use DateTime;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use App\Entity\User\Contract\UserPasswordResetInterface;

#[Entity, Table('UserPasswordReset')]
class UserPasswordReset implements UserPasswordResetInterface
{
    #[Id]
    #[GeneratedValue(strategy: "AUTO")]
    #[Column(options: ['unsigned' => true])]
    private int $IdUserPasswordReset;

    #[Column(length: 50)]
    private string $Email;

    #[Column(length: 255)]
    private string $Token;

    #[Column(options: ['default' => false])]
    private bool $IsUsed;

    #[Column]
    private DateTime $ExpireDate;


    // Getters
    public function getId(): int
    {
        return $this->IdUserPasswordReset;
    }

    public function getEmail(): string
    {
        return $this->Email;
    }

    public function getToken(): string
    {
        return $this->Token;
    }

    public function getIsUsed(): bool
    {
        return $this->IsUsed;
    }

    public function getExpireDate(): DateTime
    {
        return $this->ExpireDate;
    }


    // Setters
    public function setEmail(string $email): UserPasswordReset
    {
        $this->Email = $email;
        return $this;
    }

    public function setToken(string $token): UserPasswordReset
    {
        $this->Token = $token;
        return $this;
    }

    public function setIsUsed(bool $isUsed): UserPasswordReset
    {
        $this->IsUsed = $isUsed;
        return $this;
    }

    public function setExpireDate(DateTime $expireDate): UserPasswordReset
    {
        $this->ExpireDate = $expireDate;
        return $this;
    }
}