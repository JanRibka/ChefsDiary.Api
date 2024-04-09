<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Enums;

enum SameSiteEnum: string
{
    case STRICT = 'strict';
    case LAX = 'lax';
    case NONE = 'none';
}