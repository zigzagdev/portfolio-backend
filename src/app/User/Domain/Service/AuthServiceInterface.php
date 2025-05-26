<?php

namespace App\User\Domain\Service;

use App\User\Domain\Entity\UserEntity;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;

interface AuthServiceInterface
{
    public function attemptLogin(
        Email $email,
        Password $password
    )
    : ?UserEntity;
}