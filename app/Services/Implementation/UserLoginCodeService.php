<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Services\Implementation;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use JR\ChefsDiary\Entity\User\Contract\UserInterface;
use JR\ChefsDiary\Entity\User\Implementation\UserLoginCode;
use JR\ChefsDiary\Services\Contract\UserLoginCodeServiceInterface;

class userLoginCodeService implements UserLoginCodeServiceInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,

    ) {
    }
    public function generate(UserInterface $user): UserLoginCode
    {
        $code = random_int(100000, 999999);

        $userLoginCode = new UserLoginCode();
        $userLoginCode->setCode((string) $code);
        $userLoginCode->setExpireDate(new DateTime('+10 minutes'));
        $userLoginCode->setUser($user);

        $this->entityManager->persist($userLoginCode);
        $this->entityManager->flush();

        return $userLoginCode;
    }

    public function verify(UserInterface $user, string $code): bool
    {
        $userLoginCode = $this->entityManager->getRepository(UserLoginCode::class)->findOneBy(
            ['User' => $user, 'Code' => $code, 'IsUsed' => false]
        );

        if (!$userLoginCode) {
            return false;
        }

        if ($userLoginCode->getExpireDate() <= new DateTime()) {
            return false;
        }

        return true;
    }

    public function deactivateAllActiveCodes(UserInterface $user): void
    {
        $this->entityManager->getRepository(UserLoginCode::class)
            ->createQueryBuilder('ulc')
            ->update()
            ->set('ulc.IsUsed', '1')
            ->where('ulc.User = :user')
            ->andWhere('ulc.IsUsed = 0')
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();
    }
}