<?php

namespace JR\ChefsDiary\Entity;

use DateTime;
use Doctrine\ORM\Mapping\Id;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;

#[Entity, Table('Users')]
class User
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $IdUser;

    #[Column(type: Types::STRING, length: 50)]
    private string $Email;

    #[Column(type: Types::STRING, length: 255)]
    private string $Password;

    #[Column(type: Types::STRING, length: 255)]
    private string $Name;

    #[Column]
    private DateTime $CreatedAt;

    #[Column]
    private DateTime $UpdatedAt;
}