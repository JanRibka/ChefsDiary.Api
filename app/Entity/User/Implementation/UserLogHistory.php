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
use JR\ChefsDiary\Entity\User\Contract\UserLogHistoryInterface;

#[Entity, Table('UserLogHistory')]
class UserLogHistory implements UserLogHistoryInterface
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $IdUserLogHistory;

    #[ManyToOne(inversedBy: 'IdUser', targetEntity: User::class)]
    #[JoinColumn(name: 'IdUser', referencedColumnName: 'IdUser', options: ['unsigned' => true], nullable: false)]
    private int $IdUser;

    #[Column]
    private DateTime $LoginAttemptDate;

    #[Column]
    private bool $LoginSuccessful;


    // Getters
    public function getId(): int
    {
        return $this->IdUserLogHistory;
    }

    public function getIdUser(): int
    {
        return (int) $this->IdUser;
    }

    public function getLoginAttemptDate(): DateTime
    {
        return $this->LoginAttemptDate;
    }

    public function getLoginSuccessful(): bool
    {
        return $this->LoginSuccessful;
    }


    // Setters
}