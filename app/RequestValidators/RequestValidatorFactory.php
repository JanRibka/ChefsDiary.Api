<?php

declare(strict_types=1);

namespace JR\ChefsDiary\RequestValidators;

use RuntimeException;
use Psr\Container\ContainerInterface;
use JR\ChefsDiary\RequestValidators\RequestValidatorInterface;

class RequestValidatorFactory implements RequestValidatorFactoryInterface
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    public function make(string $class): RequestValidatorInterface
    {
        $validator = $this->container->get($class);

        if ($validator instanceof RequestValidatorInterface) {
            return $validator;
        }

        throw new RuntimeException('Failed to instantiate the request validator class "' . $class . '"');
    }
}