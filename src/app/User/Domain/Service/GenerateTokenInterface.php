<?php

namespace App\User\Domain\Service;

use App\User\Domain\Entity\UserEntity;
use App\User\Domain\ValueObject\AuthToken;

interface GenerateTokenInterface
{
    /**
     *
     * @param UserEntity $user
     * @return AuthToken
     */
    public function issue(UserEntity $user): AuthToken;
}