<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Shared\Helpers;

class BooleanHelper
{
    public static function parse()
    {
        return function (mixed $value) {
            if (in_array($value, ['true', 1, '1', true, 'yes', 'on'], true)) {
                return true;
            }

            return false;
        };
    }

}