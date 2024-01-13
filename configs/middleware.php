<?php

declare(strict_types=1);

use Slim\App;
// use Slim\Views\Twig;
use JR\ChefsDiary\Config;

// use Slim\Views\TwigMiddleware;

return function (App $app) {
    $container = $app->getContainer();
    $config = $container->get(Config::class);

    // Twig
    // TODO: Toto tu asi nemusí být je to pro views
    //$app->add(TwigMiddleware::create($app, $container->get(Twig::class)));

    // Logger
    $app->addErrorMiddleware(
        (bool) $config->get('display_error_details'),
        (bool) $config->get('log_errors'),
        (bool) $config->get('log_error_details')
    );
};