<?php

namespace App\User\Domain\Service;

use App\User\Domain\Entity\UserEntity;

interface ThrottlePasswordResetRequestServiceInterface
{
    public function checkThrottling(UserEntity $entity): void;
}