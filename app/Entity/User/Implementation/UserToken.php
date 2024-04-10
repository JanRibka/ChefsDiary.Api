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
use JR\ChefsDiary\Entity\User\Contract\UserTokenInterface;

#[Entity, Table('UserToken')]
class UserToken implements UserTokenInterface
{
    #[Id]
    #[GeneratedValue(strategy: "AUTO")]
    #[Column(options: ['unsigned' => true])]
    private int $IdUserToken;

    #[ManyToOne(inversedBy: 'IdUser', targetEntity: User::class)]
    #[JoinColumn(name: 'IdUser', referencedColumnName: 'IdUser', nullable: false)]
    private User $User;

    #[Column(length: 15)]
    private string $Domain;

    #[Column(length: 255, nullable: true)]
    private string|null $RefreshToken;



    // Getters
    public function getId(): int
    {
        return $this->IdUserToken;
    }

    public function getUser(): UserInterface
    {
        return $this->User;
    }

    public function getDomain(): string
    {
        return $this->Domain;
    }

    public function getRefreshToken(): string
    {
        return $this->RefreshToken;
    }


    // Setters
    public function setUser(UserInterface $user): UserToken
    {
        $this->User = $user;

        return $this;
    }

    public function setDomain(string $domain): UserToken
    {
        $this->Domain = $domain;

        return $this;
    }

    public function setRefreshToken(string|null $refreshToken): UserToken
    {
        $this->RefreshToken = $refreshToken;

        return $this;
    }
}