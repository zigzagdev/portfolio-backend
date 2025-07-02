<?php

namespace App\User\Infrastructure\Service;

use App\Models\User;
use App\User\Domain\Entity\UserEntity;
use Illuminate\Support\Facades\Mail;
use App\User\Domain\Service\PasswordResetNotificationServiceInterface;
use App\User\Infrastructure\Mail\PasswordResetNotification;

class PasswordResetNotificationService implements PasswordResetNotificationServiceInterface
{
    public function sendResetLink(UserEntity $entity, string $token): void
    {
        Mail::to($entity->getEmail()->getValue())->send(new PasswordResetNotification($token));
    }
}