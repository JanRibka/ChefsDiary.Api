<?php

declare(strict_types=1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use JR\ChefsDiary\Controllers\AuthController;

return function (App $app) {
    $app->group('/auth', function (RouteCollectorProxy $auth) {
        $auth->post('/register', [AuthController::class, 'register']);
        $auth->post('/login', [AuthController::class, 'login']);
        $auth->post('/logout', [AuthController::class, 'logout']);
    });
}->add();