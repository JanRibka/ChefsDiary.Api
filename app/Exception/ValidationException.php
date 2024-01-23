<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Exception;

use Throwable;
use RuntimeException;

class ValidationException extends RuntimeException
{
    public function __construct(
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}