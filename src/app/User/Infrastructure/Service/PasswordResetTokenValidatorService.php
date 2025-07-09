<?php

namespace App\User\Infrastructure\Service;

use App\Common\Domain\ValueObject\UserId;
use App\Models\User;
use App\User\Domain\Service\PasswordResetTokenValidatorInterface;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PasswordResetTokenValidatorService implements PasswordResetTokenValidatorInterface
{
    public function validate(string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        return DB::table('password_reset_requests')
            ->where('token', $token)
            ->where('requested_at', '>=', now()->subMinutes(60))
            ->exists();
    }

    public function getUserIdByToken(string $token): ?int
    {
        if (empty($token)) {
            return null;
        }

        $user = DB::table('password_reset_requests')
            ->where('token', $token)
            ->where('requested_at', '>=', now()->subMinutes(60))
            ->first();

        return $user?->user_id;
    }
}