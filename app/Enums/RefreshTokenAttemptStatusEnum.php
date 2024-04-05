<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Enums;

enum RefreshTokenAttemptStatusEnum
{
    case NO_COOKIE;
    case NO_USER;
    case USER_NOT_EQUAL;
}