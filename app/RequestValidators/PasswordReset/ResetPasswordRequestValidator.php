<?php

declare(strict_types=1);

namespace JR\ChefsDiary\RequestValidators\PasswordReset;

use Valitron\Validator;
use JR\ChefsDiary\Enums\HttpStatusCode;
use JR\ChefsDiary\Exceptions\ValidationException;
use JR\ChefsDiary\RequestValidators\RequestValidatorInterface;
use JR\ChefsDiary\Services\Contract\EntityManagerServiceInterface;

class ResetPasswordRequestValidator implements RequestValidatorInterface
{
    public function __construct(
        private readonly EntityManagerServiceInterface $entityManagerService
    ) {
    }

    public function validate(array $data): array
    {
        $v = new Validator($data);
        // Validate mandatory fields        
        $v->rule('required', 'password')->message('passwordRequired');
        $v->rule('required', 'confirmPassword')->message('confirmPasswordRequired');

        // Validate confirm password
        $v->rule('equals', 'confirmPassword', 'password')->message('confirmPasswordOneOf');

        if (!$v->validate()) {
            throw new ValidationException($v->errors(), HttpStatusCode::BAD_REQUEST->value);
        }

        return $data;
    }
}