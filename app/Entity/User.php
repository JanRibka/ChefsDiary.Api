<?php

namespace JR\ChefsDiary\Entity;

use DateTime;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;

#[Entity, Table('Users')]
class User
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $IdUser;

    #[Column]
    private string $Email;

    #[Column]
    private string $Name;

    #[Column]
    private DateTime $CreatedAt;

    #[Column]
    private DateTime $UpdatedAt;
}