<?php

namespace App\User\Domain\Service;

use App\Models\User;

interface PasswordResetNotificationServiceInterface
{
    public function sendResetLink(User $user, string $token): void;
}