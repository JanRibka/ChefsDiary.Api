<?php

declare(strict_types=1);

use Slim\App;
use JR\ChefsDiary\Controllers\AuthController;

return function (App $app) {
    $app->get('/', [AuthController::class, 'index']);
};