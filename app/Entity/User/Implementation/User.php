<?php

namespace JR\ChefsDiary\Entity\User\Implementation;

use DateTime;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;

#[Entity, Table('User')]
class User implements UserInterface
{

    #[Id]
    #[Column]
    private int $IdUser;

    #[Column]
    private string $Login;

    #[Column]
    private string $Password;

    #[Column]
    private string $RefreshToken;

    #[Column]
    private bool $IsDisabled;

    #[Column]
    private DateTime $LoginRestrictedUntil;


    // Getters
    public function getId(): int
    {
        return $this->IdUser;
    }

    public function getLogin(): string
    {
        return $this->Login;
    }

    public function getPassword(): string
    {
        return $this->Password;
    }

    public function getRefreshToken(): string
    {
        return $this->RefreshToken;
    }

    public function getIsDisabled(): bool
    {
        return $this->IsDisabled;
    }

    public function getLoginRestrictedUntil(): DateTime
    {
        return $this->LoginRestrictedUntil;
    }


    // Setters
    public function setLogin(string $login): User
    {
        $this->Login = $login;

        return $this;
    }

    public function setPassword(string $password): User
    {
        $this->Password = $password;

        return $this;
    }

    public function setRefreshToken(string $refreshToken): User
    {
        $this->RefreshToken = $refreshToken;

        return $this;
    }

    public function setIsDisabled(bool $isDisabled): User
    {
        $this->IsDisabled = $isDisabled;

        return $this;
    }

    public function setLoginRestrictedUntil(DateTime $loginRestrictedUntil): User
    {
        $this->LoginRestrictedUntil = $loginRestrictedUntil;

        return $this;
    }
}
