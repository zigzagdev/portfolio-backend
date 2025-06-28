<?php

namespace App\User\Infrastructure\Service;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\User\Domain\Service\PasswordResetNotificationServiceInterface;
use App\User\Infrastructure\Mail\PasswordResetNotification;

class PasswordResetNotificationService implements PasswordResetNotificationServiceInterface
{
    public function sendResetLink(User $user, string $token): void
    {
        Mail::to($user->email)->send(new PasswordResetNotification($token));
    }
}