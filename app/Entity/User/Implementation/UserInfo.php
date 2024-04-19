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
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;
use JR\ChefsDiary\Entity\User\Contract\UserInfoInterface;

#[Entity, Table('UserInfo')]
#[HasLifecycleCallbacks]
class UserInfo implements UserInfoInterface
{
    use HasTimestamp;

    #[Id]
    #[GeneratedValue(strategy: "AUTO")]
    #[Column(options: ['unsigned' => true])]
    private int $IdUserInfo;

    #[OneToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'IdUser', referencedColumnName: 'IdUser', options: ['unsigned' => true], nullable: false)]
    private User $User;

    #[Column(length: 25, nullable: true)]
    private string|null $FirstName;

    #[Column(length: 25, nullable: true)]
    private string|null $LastName;

    #[Column(length: 50)]
    private string $Email;

    #[Column(length: 25, nullable: true)]
    private string|null $Phone;


    // Getters
    public function getId(): int
    {
        return $this->IdUserInfo;
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
    public function setUser(UserInterface $user): UserInfo
    {
        $this->User = $user;

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