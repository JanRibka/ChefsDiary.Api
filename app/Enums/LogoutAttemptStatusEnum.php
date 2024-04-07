<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Enums;

enum LogoutAttemptStatusEnum
{
    case NO_COOKIE;
    case NO_USER;
    case LOGOUT_SUCCESS;
}