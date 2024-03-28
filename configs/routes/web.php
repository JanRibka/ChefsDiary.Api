<?php

declare(strict_types=1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface;
use JR\ChefsDiary\Middleware\AuthMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use JR\ChefsDiary\Controllers\AuthController;

return function (App $app) {

    $app->add(function (ServerRequestInterface $request, ResponseInterface $response, RequestHandlerInterface $next) {

        $response->getBody()->write('BEFORE');
        $response = $next($request, $response);
        $response->getBody()->write('AFTER');

        return $response;
        // $uri = $request->getUri();

        // echo ($uri);

        // return $next($request, $response);
    });
    // $app->group('/api', function (RouteCollectorProxy $api) {
    //     #region Authentication
    //     $api->group('/auth', function (RouteCollectorProxy $auth) {
    //         echo '1';
    //         $auth->post('/refreshToken', [AuthController::class, 'refreshToken']);
    //         $auth->post('/logout', [AuthController::class, 'logout']);
    //     })->add(AuthMiddleware::class);

    //     $api->group('/auth', function (RouteCollectorProxy $auth) {
    //         echo '2';
    //         $auth->post('/register', [AuthController::class, 'register']);
    //         $auth->post('/login', [AuthController::class, 'login']);
    //     });
    //     #endregion
    // });
};