<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Contract;

use JR\ChefsDiary\Entity\User\Contract\UserInterface;
use JR\ChefsDiary\Entity\User\Implementation\UserLoginCode;

interface UserLoginCodeServiceInterface
{
    public function generate(UserInterface $user): UserLoginCode;

    public function verify(UserInterface $user, string $code): bool;

    public function deactivateAllActiveCodes(UserInterface $user): void;
}