<?php

declare(strict_types=1);

use Slim\App;
use JR\ChefsDiary\Config;
use JR\ChefsDiary\Middleware\StartSessionMiddleware;
use JR\ChefsDiary\Middleware\ValidationExceptionMiddleware;

return function (App $app) {
    $container = $app->getContainer();
    $config = $container->get(Config::class);

    $app->add(ValidationExceptionMiddleware::class);
    $app->add(StartSessionMiddleware::class);

    $app->addBodyParsingMiddleware();
    $app->addErrorMiddleware(
        (bool) $config->get('display_error_details'),
        (bool) $config->get('log_errors'),
        (bool) $config->get('log_error_details')
    );
};