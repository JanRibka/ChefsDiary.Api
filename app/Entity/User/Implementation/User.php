<?php

namespace JR\ChefsDiary\Entity\User\Implementation;

use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;

#[Entity, Table('User')]
class User implements UserInterface
{

    // #[Id]
    // #[GeneratedValue(strategy: 'AUTO')]
    // #[Column(options: ['unsigned' => true], nullable: false)]
    private int $IdUser;

    // #[Column(length: 50, nullable: false)]
    private string $Login;

    // #[Column(length: 255, nullable: false)]
    private string $Password;


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
}
