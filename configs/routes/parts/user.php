<?php

declare(strict_types=1);

use App\Controllers\UserController;
use Slim\Routing\RouteCollectorProxy;

function getUserRoutes(RouteCollectorProxy $api)
{
    $api->group('/user', function (RouteCollectorProxy $user) {
        $user->get('/get-all', [UserController::class, 'getAll']);
    });

    return $api;
}

