<?php

namespace App\User\Infrastructure\Service;

use App\User\Domain\Service\PasswordResetTokenValidatorInterface;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PasswordResetTokenValidatorService implements PasswordResetTokenValidatorInterface
{
    public function validate(string $userId, string $token): void
    {
        if (empty($userId) || empty($token)) {
            throw new InvalidArgumentException('User ID and token must not be empty.');
        }

        if (!$this->isValidToken($userId, $token)) {
            throw new InvalidArgumentException('Invalid password reset token.');
        }
    }

    private function isValidToken($userId, $token): bool
    {
        return DB::table('password_reset_requests')
            ->where('token', $token)
            ->where('user_id', $userId)
            ->where('created_at', '>=', now()->subMinutes(60))
            ->exists();
    }
}