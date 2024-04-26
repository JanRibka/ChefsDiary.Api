<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\User\Contract;

interface UserLoginCodeInterface
{
    public function getCode(): string;
}