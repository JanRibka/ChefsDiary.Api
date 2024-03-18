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

        if (!$v->validate()) {
            throw new ValidationException($v->errors(), HttpStatusCode::BAD_REQUEST->value);
        }



        // $v->rule('email', 'email')->message('Email není platná emailová adresa');
        // // TODO: D8t regex do shared
        // // $v->rule('length', 'password', 8, 24)->message('Heslo musí mít délku 8 až 24 znaků');
        // // $v->rule('regex', 'password', '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/')->message('Heslo musí obsahovat malá písmena, velká písmena a číslice');
        // $v->rule('required', 'userName')->message('Uživatelské jméno je povinné');
        // $v->rule('required', 'password')->message('Heslo je povinné');
        // $v->rule('required', 'confirmPassword')->message('Heslo pro potvrzení je povinné');
        // $v->rule('required', 'agreement')->message('Se zpracováním osobních údajů je třeba souhlasit');

        // $v->rule('lengthMax', "userName", 25)->message('Maximální délka je 25 znaků');
        // $v->rule('lengthMax', "password", 25)->message('Maximální délka je 25 znaků');

        // if (!$v->validate()) {
        //     throw new ValidationException($v->errors(), HttpStatusCode::BAD_REQUEST->value);
        // }




        // if (!$v->validate()) {
        //     throw new ValidationException($v->errors(), HttpStatusCode::BAD_REQUEST->value);
        // }

        // // Validate password equals
        // $v->rule('equals', 'password', 'confirmPassword')->message('Hesla se neshodují');

        // if (!$v->validate()) {
        //     throw new ValidationException($v->errors(), HttpStatusCode::BAD_REQUEST->value);
        // }

        // // Validate user exists
        // $v->rule(
        //     fn($field, $value, $params, $fields) => !$this->entityManagerService->getRepository(User::class)->count(
        //         ['Login' => $value]
        //     ),
        //     'login'
        // )->message('Uživatel s daným emailem již existuje');

        // // Validate user name exists
        // $v->rule(
        //     fn($field, $value, $params, $fields) => !$this->entityManagerService->getRepository(UserInfo::class)->count(
        //         ['UserName' => $value]
        //     ),
        //     'userName'
        // )->message('Uživatelské jméno již existuje');

        // if (!$v->validate()) {
        //     throw new ValidationException($v->errors(), HttpStatusCode::CONFLICT->value);
        // }

        return $data;
    }
}