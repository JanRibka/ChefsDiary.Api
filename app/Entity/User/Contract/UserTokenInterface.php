<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\User\Contract;

use JR\ChefsDiary\Entity\User\Implementation\UserToken;

interface UserTokenInterface
{
    public function getUser(): UserInterface;
    public function setRefreshToken(string|null $refreshToken): UserToken;
}