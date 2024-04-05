<?php

declare(strict_types=1);

use Slim\Routing\RouteCollectorProxy;
use JR\ChefsDiary\Controllers\AuthController;
use JR\ChefsDiary\Middleware\VerifyTokenMiddleware;

function getAuthRoutes(RouteCollectorProxy $api)
{
    $api->group('/auth', function (RouteCollectorProxy $auth) {
        $auth->post('/refreshToken', [AuthController::class, 'refreshToken']);
        $auth->post('/logout', [AuthController::class, 'logout'])->add(VerifyTokenMiddleware::class);
    });

    $api->group('/auth', function (RouteCollectorProxy $auth) {
        $auth->post('/register', [AuthController::class, 'register']);
        $auth->post('/login', [AuthController::class, 'login']);
    });

    return $api;
}
