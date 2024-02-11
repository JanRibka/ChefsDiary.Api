<?php

declare(strict_types=1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use JR\ChefsDiary\Middleware\AuthMiddleware;
use JR\ChefsDiary\Controllers\AuthController;

return function (App $app) {
    #region Authentication
    $app->group('/auth', function (RouteCollectorProxy $auth) {
        $auth->post('/logout', [AuthController::class, 'logout']);
    })->add(AuthMiddleware::class);

    $app->group('/auth', function (RouteCollectorProxy $auth) {
        $auth->post('/register', [AuthController::class, 'register']);
        $auth->post('/login', [AuthController::class, 'login']);
    });
    #endregion
};