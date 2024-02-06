<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\User;

use DateTime;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;

class UserInfo implements UserInfoInterface
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $IdUserInfo;

    #[Column(options: ['unsigned' => true])]
    private int $IdUser;

    private string $UserName;

    private string $Email;

    private DateTime $CreatedAt;


    // Getters
    public function getId(): int
    {
        return $this->IdUserInfo;
    }

}