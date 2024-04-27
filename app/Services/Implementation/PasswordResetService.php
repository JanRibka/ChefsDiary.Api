<?php

declare(strict_types=1);

namespace App\Services\Implementation;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User\Implementation\UserPasswordReset;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;
use App\Entity\User\Contract\UserPasswordResetInterface;
use App\Services\Contract\PasswordResetServiceInterface;
use JR\ChefsDiary\Repositories\Implementation\UserRepository;

class PasswordResetService implements PasswordResetServiceInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function generate(string $email): UserPasswordResetInterface
    {
        $passwordReset = new UserPasswordReset();

        $passwordReset->setToken(bin2hex(random_bytes(32)));
        $passwordReset->setExpireDate(new DateTime('+1 hour'));
        $passwordReset->setEmail($email);

        $this->entityManager->persist($passwordReset);
        $this->entityManager->flush();

        return $passwordReset;
    }

    public function deactivateAllPasswordResets(string $email): void
    {
        $this->userRepository->deactivateAllUserPasswordResets($email);
    }

    public function findByToken(string $token): UserPasswordResetInterface|null
    {
        return $this->userRepository->findUserPasswordResetByToken($token);
    }

    public function updatePassword(UserInterface $user, string $password): void
    {
        $this->entityManager->wrapInTransaction(function () use ($user, $password) {
            $userInfo = $this->userRepository->getUserInfoByUserId($user->getId());
            $this->deactivateAllPasswordResets($userInfo->getEmail());

            $this->userRepository->updatePassword($user, $password);
        });
    }

    public function getUserByEmail(string $email): UserInterface|null
    {
        $userInfo = $this->userRepository->getUserInfoByEmail($email);

        return $userInfo?->getUser();
    }
}