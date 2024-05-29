<?php

namespace JR\ChefsDiary\Entity\User\Implementation;

use DateTime;
use Ramsey\Uuid\Uuid;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;

#[Entity, Table('User')]
class User implements UserInterface
{

    #[Id]
    #[GeneratedValue(strategy: "AUTO")]
    #[Column(options: ['unsigned' => true])]
    private int $IdUser;

    #[Column(length: 36)]
    private string $Uuid;

    #[Column(length: 25)]
    private string $Login;

    #[Column(length: 75)]
    private string $Password;

    #[Column(options: ['default' => false], nullable: true)]
    private bool|null $IsDisabled;

    #[Column(nullable: true)]
    private DateTime|null $LoginRestrictedUntil;

    #[Column(options: ['default' => false], nullable: true)]
    private bool|null $TwoFactor;


    // Getters
    public function getId(): int
    {
        return $this->IdUser;
    }

    public function getUuid(): string
    {
        return $this->Uuid;
    }

    public function getLogin(): string
    {
        return $this->Login;
    }

    public function getPassword(): string
    {
        return $this->Password;
    }

    public function getIsDisabled(): bool
    {
        return $this->IsDisabled;
    }

    public function getLoginRestrictedUntil(): DateTime
    {
        return $this->LoginRestrictedUntil;
    }

    public function getTwoFactor(): bool
    {
        return $this->TwoFactor;
    }


    // Setters
    public function setLogin(string $login): User
    {
        $this->Login = $login;

        return $this;
    }

    public function setUuid(): User
    {
        $this->Uuid = Uuid::uuid4();

        return $this;
    }

    public function setPassword(string $password): User
    {
        $this->Password = $password;

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

    public function setTwoFactor(bool $twoFactor): User
    {
        $this->TwoFactor = $twoFactor;

        return $this;
    }
}
