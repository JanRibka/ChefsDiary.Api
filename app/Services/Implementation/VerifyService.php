<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Implementation;

use RuntimeException;
use JR\ChefsDiary\Mail\SignUpEmail;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;
use JR\ChefsDiary\Services\Contract\VerifyServiceInterface;
use JR\ChefsDiary\Repositories\Contract\UserRepositoryInterface;

class VerifyService implements VerifyServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly SignUpEmail $signUpEmail
    ) {
    }
    public function verify(UserInterface $user, array $args): void
    {
        $userInfo = $this->userRepository->getUserInfoByUserId($user->getId());
        // TODO: Misto id pouzivat uuid
        if (
            !hash_equals((string) $user->getUuid(), $args['uuid'])
            || !hash_equals(sha1($userInfo->getEmail()), $args['hash'])
        ) {
            throw new RuntimeException('Verification failed');
        }

        if (!$userInfo->getVerifiedAt()) {
            $this->userRepository->verifyUser($user);
        }
    }

    public function resend(UserInterface $user): void
    {
        $userInfo = $this->userRepository->getUserInfoByUserId($user->getId());

        $this->signUpEmail->send($userInfo);
    }
}