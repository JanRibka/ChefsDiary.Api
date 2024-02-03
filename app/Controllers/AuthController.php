<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Controllers;

use JR\ChefsDiary\Entity\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    public function __construct()
    {
    }


    public function register(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $user = new User();

        $user->setEmail($data["email"]);
        $user->setPassword($data["password"]);

        return $response;
    }

    // public function login(Request $request, Response $response): Response
    // {

    // }

    // public function logout(Request $request, Response $response): Response
    // {

    // }
}