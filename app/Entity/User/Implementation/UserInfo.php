<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\User\Implementation;

use DateTime;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use JR\ChefsDiary\Entity\User\Contract\UserInfoInterface;

#[Entity, Table('UserInfo')]
class UserInfo implements UserInfoInterface
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $IdUserInfo;

    #[Column(options: ['unsigned' => true])]
    private int $IdUser;

    private string $UserName;

    private string $Email;

    private string $Phone;

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