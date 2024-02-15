<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Enums;

enum AuthAttemptStatusEnum
{
    case FAILED;
    case TWO_FACTOR;
    case DISABLED;
}