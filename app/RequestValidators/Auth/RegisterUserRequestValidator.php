<?php

declare(strict_types=1);

namespace JR\ChefsDiary\RequestValidators\Auth;

use Valitron\Validator;
use JR\ChefsDiary\Enums\HttpStatusCode;
use JR\ChefsDiary\Exceptions\ValidationException;
use JR\ChefsDiary\Entity\User\Implementation\User;
use JR\ChefsDiary\Entity\User\Implementation\UserInfo;
use JR\ChefsDiary\RequestValidators\RequestValidatorInterface;
use JR\ChefsDiary\Services\Contract\EntityManagerServiceInterface;

class RegisterUserRequestValidator implements RequestValidatorInterface
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
        $v->rule('required', 'email')->message('emailRequired');
        $v->rule('required', 'password')->message('passwordRequired');
        $v->rule('required', 'confirmPassword')->message('confirmPasswordRequired');

        // Validate login
        $v->rule('lengthMin', "login", 4)->message('loginMinLength|4');
        $v->rule('lengthMax', "login", 24)->message('loginMaxLength|24');
        $v->rule('regex', 'login', '/^' . LOWER_UPPERCASE_REGEX . '/')->message('loginStartWithLetter');
        $v->rule('regex', 'login', '/' . LowerUpperCaseNumberSpecialCharRegex('-_') . '$/')->message('loginAllowedCharacters');
        $v->rule(
            fn($field, $value, $params, $fields) => !$this->entityManagerService->getRepository(User::class)->count(
                ['Login' => $value]
            ),
            'login'
        )->message('userNameExists');

        // Validate email
        $v->rule('email', 'email')->message('emailInvalid');
        $v->rule('regex', 'email', '/' . EMAIL_END_REGEX . '/')->message('emailInvalid');
        $v->rule(
            fn($field, $value, $params, $fields) => !$this->entityManagerService->getRepository(UserInfo::class)->count(
                ['Email' => $value]
            ),
            'email'
        )->message('emailExists');

        // Validate password
        $v->rule('lengthMin', "password", 8)->message('passwordMinLength|8');
        $v->rule('lengthMax', "password", 24)->message('passwordMaxLength|24');
        $v->rule('regex', 'password', '/' . LOWERCASE_REGEX . '/')->message('passwordLoweCase');
        $v->rule('regex', 'password', '/' . UPPERCASE_REGEX . '/')->message('passwordUpperCase');
        $v->rule('regex', 'password', '/' . NUMBERS_REGEX . '/')->message('passwordNumbers');

        // Validate confirm password
        $v->rule('equals', 'confirmPassword', 'password')->message('confirmPasswordOneOf');

        if (!$v->validate()) {
            throw new ValidationException($v->errors(), HttpStatusCode::BAD_REQUEST->value);
        }

        return $data;
    }
}