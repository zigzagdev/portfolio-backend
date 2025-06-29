<?php

namespace App\User\Domain\Service;

use App\User\Domain\Entity\UserEntity;

interface PasswordResetNotificationServiceInterface
{
    public function sendResetLink(UserEntity $entity, string $token): void;
}