<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Enums;

enum UserRoleEnum: int
{
    case USER = 2001;
    case EDITOR = 1984;
    case ADMIN = 5150;
}