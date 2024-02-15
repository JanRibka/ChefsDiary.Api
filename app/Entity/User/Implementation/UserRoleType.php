<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\User\Implementation;

use Doctrine\ORM\Mapping\Id;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use JR\ChefsDiary\Entity\User\Contract\UserRoleTypeInterface;

#[Entity, Table('UserRoleType')]
class UserRoleType implements UserRoleTypeInterface
{
    #[Id]
    #[GeneratedValue(strategy: "AUTO")]
    #[Column(options: ['unsigned' => true])]
    private int $IdUserRoleType;

    #[Column(length: 20)]
    private string $Code;

    #[Column(type: Types::SMALLINT)]
    private int $Value;

    #[Column(length: 20)]
    private string $Description;


    // Getters
    public function getId(): int
    {
        return $this->IdUserRoleType;
    }

    public function getCode(): string
    {
        return $this->Code;
    }

    public function getValue(): int
    {
        return $this->Value;
    }

    public function getDescription(): string
    {
        return $this->Description;
    }
}