<?php

declare(strict_types=1);

namespace JR\ChefsDiary\RequestValidators\Auth;

use Valitron\Validator;
use JR\ChefsDiary\Enums\HttpStatusCodeEnum;
use JR\ChefsDiary\Exception\ValidationException;
use JR\ChefsDiary\RequestValidators\RequestValidatorInterface;
use JR\ChefsDiary\Services\Contract\EntityManagerServiceInterface;

class UserLoginRequestValidator implements RequestValidatorInterface
{
    public function __construct(
        private readonly EntityManagerServiceInterface $entityManagerService
    ) {
    }

    public function validate(array $data): array
    {
        $v = new Validator($data);

        // Validate mandatory fields
        $v->rule('required', 'login')->message('loginRequired');
        $v->rule('required', 'password')->message('passwordRequired');

        if (!$v->validate()) {
            throw new ValidationException($v->errors(), HttpStatusCodeEnum::BAD_REQUEST->value);
        }

        return $data;
    }
}