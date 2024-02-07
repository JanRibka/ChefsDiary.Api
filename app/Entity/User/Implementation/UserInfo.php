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
use JR\ChefsDiary\Entity\User\Contract\UserInfoInterface;

#[Entity, Table('UserInfo')]
class UserInfo implements UserInfoInterface
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $IdUserInfo;

    #[OneToOne(mappedBy: 'IdUser', targetEntity: User::class)]
    #[JoinColumn(name: 'IdUser', referencedColumnName: 'IdUser', options: ['unsigned' => true], nullable: false)]
    private int $IdUser;

    #[Column(length: 50, nullable: true)]
    private string $UserName;

    #[Column(length: 50, nullable: true)]
    private string $Email;

    #[Column(length: 50, nullable: true)]
    private string $Phone;

    //TODO: Bude v jinem modelu
    #[Column]
    private DateTime $CreatedAt;


    // Getters
    public function getId(): int
    {
        return $this->IdUserInfo;
    }

    public function getIdUser(): int
    {
        return $this->IdUser;
    }
}