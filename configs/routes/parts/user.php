<?php

declare(strict_types=1);

use Slim\Routing\RouteCollectorProxy;
use JR\ChefsDiary\Controllers\UserController;

function getUserRoutes(RouteCollectorProxy $api)
{
    $api->group('/user', function (RouteCollectorProxy $user) {
        $user->get('/getAll', [UserController::class, 'getAll']);
        $user->get('/getUserForEdit', [UserController::class, 'getUserForEdit']);
    });

    return $api;
}

