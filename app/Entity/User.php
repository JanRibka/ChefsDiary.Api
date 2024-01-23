<?php

namespace JR\ChefsDiary\Entity;

use DateTime;
use Doctrine\ORM\Mapping\Id;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[Entity, Table('Users')]
#[HasLifecycleCallbacks]
class User
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $IdUser;

    #[Column(type: Types::STRING, length: 50)]
    private string $Email;

    #[Column(length: 255)]
    private string $Password;

    #[Column]
    private DateTime $CreatedAt;

    #[Column(nullable: true)]
    private DateTime $UpdatedAt;


    public function getId(): int
    {
        return $this->IdUser;
    }

    public function updateTimestamp(LifecycleEventArgs $args): void
    {
        if (!isset($this->CreatedAt)) {
            $this->CreatedAt = new DateTime();
        }

        $this->UpdatedAt = new DateTime();
    }

    public function getEmail(): string
    {
        return $this->Email;
    }

    public function setEmail(string $email): User
    {
        $this->Email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->Password;
    }

    public function setPassword(string $password): User
    {
        $this->Password = $password;

        return $this;
    }
}