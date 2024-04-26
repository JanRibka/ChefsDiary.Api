<?php

declare(strict_types=1);

namespace App\RequestValidators;

use Valitron\Validator;
use JR\ChefsDiary\Enums\HttpStatusCode;
use JR\ChefsDiary\Exceptions\ValidationException;
use JR\ChefsDiary\RequestValidators\RequestValidatorInterface;

class TwoFactorLoginRequestValidator implements RequestValidatorInterface
{
    public function validate(array $data): array
    {
        $v = new Validator($data);

        // Validate mandatory fields
        $v->rule('required', 'email')->message('emailRequired');
        $v->rule('required', 'code')->message('codeRequired');

        // Validate email
        $v->rule('email', 'email')->message('emailInvalid');
        $v->rule('regex', 'email', '/' . EMAIL_END_REGEX . '/')->message('emailInvalid');

        if (!$v->validate()) {
            throw new ValidationException($v->errors(), HttpStatusCode::BAD_REQUEST->value);
        }

        return $data;
    }
}
