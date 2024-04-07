<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Enums;

enum SameSiteEnum: string
{
    case STRICT = 'Strict';
    case LAX = 'Lax';
    case NONE = 'None';
}