<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Repositories\Implementation;

use DateTime;
use JR\ChefsDiary\Enums\DomainEnum;
use JR\ChefsDiary\Enums\UserRoleEnum;
use JR\ChefsDiary\DataObjects\Data\UserData;
use JR\ChefsDiary\DataObjects\Data\UserTokenData;
use JR\ChefsDiary\Entity\User\Implementation\User;
use JR\ChefsDiary\DataObjects\Data\RegisterUserData;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;
use JR\ChefsDiary\Entity\User\Implementation\UserInfo;
use JR\ChefsDiary\Services\Implementation\HashService;
use JR\ChefsDiary\Entity\User\Implementation\UserRoles;
use JR\ChefsDiary\Entity\User\Implementation\UserToken;
use JR\ChefsDiary\Entity\User\Contract\UserTokenInterface;
use JR\ChefsDiary\Entity\User\Implementation\UserRoleType;
use JR\ChefsDiary\Entity\User\Implementation\UserLogHistory;
use JR\ChefsDiary\Repositories\Contract\UserRepositoryInterface;
use JR\ChefsDiary\Services\Contract\EntityManagerServiceInterface;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerServiceInterface $entityManagerService,
        private readonly HashService $hashService
    ) {

    }

    public function getById(int $userId): ?UserInterface
    {
        return $this->entityManagerService->find(User::class, $userId);
    }

    public function getByLogin(string $login): ?UserInterface
    {
        return $this->entityManagerService->getRepository(User::class)->findOneBy(['Login' => $login]);
    }

    public function getByRefreshToken(string $refreshToken): ?UserInterface
    {
        return $this->entityManagerService->getRepository(User::class)->findOneBy(['RefreshToken' => $refreshToken]);
    }

    public function createUser(RegisterUserData $data): UserInterface
    {
        $user = new User();

        $this->entityManagerService->transactional(function () use ($user, $data) {
            // Insert user
            $user
                ->setUuid()
                ->setLogin($data->login)
                ->setPassword($this->hashService->hashPassword($data->password))
                ->setIsDisabled(false);

            $idUser = $this->entityManagerService->sync($user);

            // Insert userInfo
            $user = $this->entityManagerService->find(User::class, $idUser);
            $userInfo = new UserInfo();

            $userInfo
                ->setUser($user)
                ->setEmail($data->email)
                ->setCreatedAt(new DateTime());

            $this->entityManagerService->sync($userInfo);

            // Insert user role
            $userRoleTypes = $this->entityManagerService->getRepository(UserRoleType::class)
                ->findBy(
                    [
                        'Value' =>
                            [
                                UserRoleEnum::USER->value,
                                UserRoleEnum::EDITOR->value
                            ]
                    ]
                );


            foreach ($userRoleTypes as $item) {
                $userRoles = new UserRoles();
                $userRoles
                    ->setUser($user)
                    ->setUserRoleType($item);

                $this->entityManagerService->persist($userRoles);
            }

            $this->entityManagerService->flush();
            $this->entityManagerService->clear();
        });

        return $user;
    }

    public function logLoginAttempt(UserInterface $user, bool $successful): void
    {
        $userLogHistory = new UserLogHistory();

        $userLogHistory
            ->setLoginAttemptDate(new DateTime())
            ->setLoginSuccessful($successful)
            ->setUser($user);

        if (!!$user) {
            $this->entityManagerService->sync($userLogHistory);
        }
    }

    public function getUserRolesByUserId(int $idUser): array
    {
        return $this->entityManagerService->getRepository(UserRoles::class)->findBy(['User' => $idUser]);
    }

    public function update(UserInterface $user, UserData $data): void
    {
        // if (!!$data->refreshToken) {
        //     $user->setRefreshToken($data->refreshToken);
        // }

        $this->entityManagerService->sync($user);
    }

    public function getRefreshTokenByUserIdAndDomain(int $idUser, string|null $domain): UserTokenInterface|null
    {
        $criteria = ['User' => $idUser];

        if ($domain !== null) {
            $criteria['Domain'] = $domain;
        }

        return $this->entityManagerService->getRepository(UserToken::class)
            ->findOneBy($criteria);
    }

    public function updateUserToken(UserTokenInterface $userToken, UserTokenData $data): void
    {
        if (!!$data->refreshToken) {
            $userToken->setRefreshToken($data->refreshToken);
        }

        $this->entityManagerService->sync($userToken);
    }

    public function createUpdateRefreshToken(UserInterface $user, string|null $token, DomainEnum $domain): void
    {
        $refreshToken = $this->getRefreshTokenByUserIdAndDomain($user->getId(), $domain->value);

        if ($refreshToken) {
            $this->updateUserToken(
                $refreshToken,
                new UserTokenData(
                    $token
                )
            );
        } else if ($token !== null) {
            $userToken = new UserToken();

            $userToken
                ->setUser($user)
                ->setDomain($domain->value)
                ->setRefreshToken($token);

            $this->entityManagerService->sync($userToken);
        }
    }
}