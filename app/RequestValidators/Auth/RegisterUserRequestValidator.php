<?php

declare(strict_types=1);

namespace JR\ChefsDiary\RequestValidators\Auth;

use Valitron\Validator;
use JR\ChefsDiary\Enums\HttpStatusCode;
use JR\ChefsDiary\Exception\ValidationException;
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
        $v->rule('email', 'email')->message('Email není platná emailová adresa');

        if (!!$data['email']) {
            $v->rule('email', 'email')->message('Email není platná emailová adresa');
        }
        // TODO: D8t regex do shared
        // $v->rule('length', 'password', 8, 24)->message('Heslo musí mít délku 8 až 24 znaků');
        // $v->rule('regex', 'password', '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/')->message('Heslo musí obsahovat malá písmena, velká písmena a číslice');
        $v->rule('required', 'userName')->message('Uživatelské jméno je povinné');
        $v->rule('required', 'password')->message('Heslo je povinné');
        $v->rule('required', 'confirmPassword')->message('Heslo pro potvrzení je povinné');
        $v->rule('required', 'agreement')->message('Se zpracováním osobních údajů je třeba souhlasit');

        $v->rule('lengthMax', "userName", 25)->message('Maximální délka je 25 znaků');
        $v->rule('lengthMax', "password", 25)->message('Maximální délka je 25 znaků');

        if (!$v->validate()) {
            throw new ValidationException($v->errors(), HttpStatusCode::BAD_REQUEST->value);
        }

        // Validate login length
        $v->rule('lengthMax', "login", 25)->message('Maximální délka je 25 znaků');

        if (!$v->validate()) {
            throw new ValidationException($v->errors(), HttpStatusCode::BAD_REQUEST->value);
        }

        // Validate password equals
        $v->rule('equals', 'password', 'confirmPassword')->message('Hesla se neshodují');

        if (!$v->validate()) {
            throw new ValidationException($v->errors(), HttpStatusCode::BAD_REQUEST->value);
        }

        // Validate user exists
        $v->rule(
            fn($field, $value, $params, $fields) => !$this->entityManagerService->getRepository(User::class)->count(
                ['Login' => $value]
            ),
            'login'
        )->message('Uživatel s daným emailem již existuje');

        // Validate user name exists
        $v->rule(
            fn($field, $value, $params, $fields) => !$this->entityManagerService->getRepository(UserInfo::class)->count(
                ['UserName' => $value]
            ),
            'userName'
        )->message('Uživatelské jméno již existuje');

        if (!$v->validate()) {
            throw new ValidationException($v->errors(), HttpStatusCode::CONFLICT->value);
        }

        return $data;
    }
}