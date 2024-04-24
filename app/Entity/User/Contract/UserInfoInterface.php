<?php

declare(strict_types=1);

namespace JR\ChefsDiary\Entity\User\Contract;

use DateTime;
use JR\ChefsDiary\Entity\User\Implementation\UserInfo;

interface UserInfoInterface
{
    public function getId(): int;
    public function getUser(): UserInterface;
    public function getEmail(): string|null;
    public function getVerifiedAt(): DateTime|null;
    public function setVerifiedAt(DateTime $verifiedAt): UserInfo;
}