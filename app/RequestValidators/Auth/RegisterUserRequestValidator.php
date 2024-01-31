<?php

declare(strict_types=1);

namespace JR\ChefsDiary\RequestValidators\Auth;

use Valitron\Validator;
use JR\ChefsDiary\Entity\User;
use JR\ChefsDiary\Enums\HttpStatusCodeEnum;
use JR\ChefsDiary\Exception\ValidationException;
use JR\ChefsDiary\Services\Contracts\IEntityManagerService;
use JR\ChefsDiary\RequestValidators\RequestValidatorInterface;

class RegisterUserRequestValidation implements IRequestValidator
{
    public function __construct(private readonly IEntityManagerService $entityManagerService)
    {
    }

    public function validate(array $data): array
    {
        // Validate email and password required
        $v = new Validator();
        $v->rule('required', ['Email', 'Password', 'ConfirmPassword'])->message('Email and password is required');

        if (!$v->validate()) {
            throw new ValidationException($v->message(), HttpStatusCodeEnum::BAD_REQUEST->value());
        }

        // Validate email address
        // $v = new Validator();
        // $v->rule('email', 'Email')->message("Email is not a valid email address");

        // if (!$v->validate()) {
        //     throw new ValidationException($v->message(), HttpStatusCodeEnum::BAD_REQUEST);
        // }

        // // Validate password and confirmPassword are not equal 
        // $v = new Validator();
        // $v->rule('equals', 'password', 'ConfirmPassword')->message('Password and ConfirmPassword are not equal');

        // if (!$v->validate()) {
        //     throw new ValidationException($v->message(), HttpStatusCodeEnum::BAD_REQUEST);
        // }

        // // Validate user already exists
        // $v = new Validator();        
        // $v->rule(fn($field, $value, $params, $field) => !$this->entityManagerService(User::class))->message('User with the given email address already exists');

        // if (!$v->validate()) {
        //     throw new ValidationException($v->errors(), $v->message, HttpStatusCodeEnum::BAD_REQUEST);
        // }

        return $data;
    }
}