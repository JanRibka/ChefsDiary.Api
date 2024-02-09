<?php

declare(strict_types=1);

namespace JR\ChefsDiary\RequestValidators\Auth;

use Valitron\Validator;
use JR\ChefsDiary\Enums\HttpStatusCodeEnum;
use JR\ChefsDiary\Exception\ValidationException;
use JR\ChefsDiary\Entity\User\Implementation\User;
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
        $v->rule('required', 'login')->message('Email je povinný');

        if (!!$data['login']) {
            $v->rule('email', 'login')->message('Email není platná emailová adresa');
        }

        $v->rule('required', 'password')->message('Heslo je povinné');
        $v->rule('required', 'confirmPassword')->message('Heslo pro potvrzení je povinné');
        $v->rule('required', 'agreement')->message('Se zpracováním osobních údajů je třeba souhlasit');

        if (!$v->validate()) {
            throw new ValidationException($v->errors(), HttpStatusCodeEnum::BAD_REQUEST->value);
        }

        // Validate password equals
        $v->rule('equals', 'password', 'confirmPassword')->message('Hesla se neshodují');

        if (!$v->validate()) {
            throw new ValidationException($v->errors(), HttpStatusCodeEnum::BAD_REQUEST->value);
        }

        // Validate user exists
        $v->rule(
            fn($field, $value, $params, $fields) => !$this->entityManagerService->getRepository(User::class)->count(
                ['Login' => $value]
            ),
            'login'
        )->message('Uživatel s daným emailem již existuje');

        if (!$v->validate()) {
            throw new ValidationException($v->errors(), HttpStatusCodeEnum::CONFLICT->value);
        }

        return $data;
    }
}