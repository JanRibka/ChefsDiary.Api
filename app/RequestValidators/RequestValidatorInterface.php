<?php

declare(strict_types=1);

namespace JR\ChefsDiary\RequestValidators;

interface RequestValidatorInterface
{
    /**
     * Summary of validate
     * @param array $data
     * @return array
     * @author Jan Ribka
     */
    public function validate(array $data): array;
}