<?php

declare(strict_types=1);

namespace App\Entity\User\Contract;

use DateTime;

interface UserPasswordResetInterface
{
    public function getEmail(): string;
    public function getToken(): string;
    public function getExpireDate(): DateTime;
}