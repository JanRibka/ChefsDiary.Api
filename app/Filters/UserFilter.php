<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Filters;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use JR\ChefsDiary\Shared\Interfaces\OwnableInterface;

class UserFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (!$targetEntity->getReflectionClass()->implementsInterface(OwnableInterface::class)) {
            return '';
        }

        return $targetTableAlias . 'IdUser = ' . $this->getParameter('IdUser');
    }
}