<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use JR\ChefsDiary\Entity\User\Implementation\UserInfo;
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

    /**
     * Get all users
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return Response
     * @author Jan Ribka
     */
    public function getAll(Request $request, Response $response)
    {
        $params = $this->requestService->getDataTableQueryParameters($request);
        $users = $this->userService->getPaginatedUsers($params);

        $transformer = function (UserInfo $userInfo) {
            return [
                'uuid' => $userInfo->getUser()->getUuid(),
                'login' => $userInfo->getUser()->getLogin(),
                'isDisabled' => $userInfo->getUser()->getIsDisabled(),
                'email' => $userInfo->getEmail(),
                'createdAt' => $userInfo->getCreatedAt()->format('Y-m-d H:i:s'),
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