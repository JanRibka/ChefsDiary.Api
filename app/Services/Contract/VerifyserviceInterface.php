<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Contract;

use JR\ChefsDiary\Entity\User\Contract\UserInterface;

interface VerifyServiceInterface
{
    public function verify(UserInterface $user, array $args): void;

    public function resend(UserInterface $user): void;
}