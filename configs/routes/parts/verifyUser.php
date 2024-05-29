<?php

declare(strict_types=1);

use Slim\Routing\RouteCollectorProxy;
use JR\ChefsDiary\Controllers\VerifyUserController;
use JR\ChefsDiary\Middleware\ValidateSignatureMiddleware;

function getVerifyUserRoutes(RouteCollectorProxy $api)
{
    $api->group('/verifyUser', function (RouteCollectorProxy $verifyUser) {
        $verifyUser->get('/verify/{uuid}/{hash}', [VerifyUserController::class, 'verify'])
            ->setName('verify')
            ->add(ValidateSignatureMiddleware::class);
        $verifyUser->get('/resend', [VerifyUserController::class, 'resend']);
    });

    return $api;
}

