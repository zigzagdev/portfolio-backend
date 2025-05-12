<?php

namespace App\User\Domain\Service;

use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;

class UserDuplicationChecker
{

    public function __construct(
        readonly private UserRepositoryInterface $userRepository
    ) {
    }

    public function existsCheck(Email $email): bool
    {
        return $this->userRepository->existsByEmail($email);
    }
}