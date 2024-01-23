<?php

declare(strict_types=1);

use Slim\App;
use JR\ChefsDiary\Controllers\AuthController;

return function (App $app) {
    $app->post('/register', [AuthController::class, 'register']);
    $app->post('/login', [AuthController::class, 'login']);
    $app->post('/logout', [AuthController::class, 'logout']);
};