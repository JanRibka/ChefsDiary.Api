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
use JR\ChefsDiary\Entity\User\Contract\UserLogHistoryInterface;

#[Entity, Table('UserLogHistory')]
class UserLogHistory implements UserLogHistoryInterface
{
    #[Id]
    #[GeneratedValue(strategy: "AUTO")]
    #[Column(options: ['unsigned' => true])]
    private int $IdUserLogHistory;

    #[ManyToOne(inversedBy: 'IdUser', targetEntity: User::class)]
    #[JoinColumn(name: 'IdUser', referencedColumnName: 'IdUser', nullable: false)]
    private User $User;

    #[Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $LoginAttemptDate;

    #[Column(options: ['default' => false])]
    private bool $LoginSuccessful;


    // Getters
    public function getId(): int
    {
        return $this->IdUserLogHistory;
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
    public function setLoginAttemptDate(DateTime $loginAttemptDate): UserLogHistory
    {
        $this->LoginAttemptDate = $loginAttemptDate;

        return $this;
    }

    public function setLoginSuccessful(bool $loginSuccessful): UserLogHistory
    {
        $this->LoginSuccessful = $loginSuccessful;

        return $this;
    }

    public function setUser(UserInterface $user): UserLogHistory
    {
        $this->User = $user;

        return $this;
    }
}