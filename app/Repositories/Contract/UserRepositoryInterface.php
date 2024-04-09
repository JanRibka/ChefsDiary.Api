<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Repositories\Contract;

use JR\ChefsDiary\Enums\DomainEnum;
use JR\ChefsDiary\DataObjects\Data\UserData;
use JR\ChefsDiary\DataObjects\Data\UserTokenData;
use JR\ChefsDiary\DataObjects\Data\RegisterUserData;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;
use JR\ChefsDiary\Entity\User\Contract\UserRolesInterface;
use JR\ChefsDiary\Entity\User\Contract\UserTokenInterface;

interface UserRepositoryInterface
{
    public function getById(int $userId): ?UserInterface;

    public function getByLogin(string $login): ?UserInterface;

    public function getByRefreshToken(string $refreshToken): ?UserInterface;

    public function createUser(RegisterUserData $data): UserInterface;

    public function logLoginAttempt(UserInterface $user, bool $successful): void;

    /**
     * Get user roles by user id
     * @param int $userId
     * @return UserRolesInterface[]
     * @author Jan Ribka
     */
    public function getUserRolesByUserId(int $idUser): array;

    public function update(UserInterface $user, UserData $data): void;

    public function getRefreshTokenByUserIdAndDomain(int $idUser, string|null $domain): UserTokenInterface|null;

    public function updateUserToken(UserTokenInterface $userToken, UserTokenData $data): void;

    public function createUpdateRefreshToken(UserInterface $user, string|null $token, DomainEnum $domain): void;
}