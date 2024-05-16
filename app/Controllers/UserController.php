<?php

declare(strict_types=1);

namespace App\Controllers;

use JR\ChefsDiary\Entity\User\Implementation\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use JR\ChefsDiary\Services\Contract\UserServiceInterface;
use JR\ChefsDiary\Services\Implementation\RequestService;
use JR\ChefsDiary\Shared\ResponseFormatter\ResponseFormatter;

class UserController
{
    public function __construct(
        private readonly RequestService $requestService,
        private readonly UserServiceInterface $userService,
        private readonly ResponseFormatter $responseFormatter
    ) {
    }

    public function getAll(Request $request, Response $response)
    {
        $params = $this->requestService->getDataTableQueryParameters($request);
        $users = $this->userService->getPaginatedUsers($params);

        $transformer = function (User $user) {
            return [
                'Uuid' => $user->getUuid(),
                'Login' => $user->getLogin(),
                'IsDisabled' => $user->getIsDisabled(),
            ];
        };

        $totalUsers = count($users);

        return $this->responseFormatter->asDataTable(
            $response,
            array_map($transformer, (array) $users->getIterator()),
            $params->draw,
            $totalUsers
        );
    }
}