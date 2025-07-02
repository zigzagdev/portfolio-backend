<?php

namespace App\User\Infrastructure\Service;

use App\Models\User;
use App\User\Domain\Service\PasswordResetGenerateTokenServiceInterface;
use App\User\Domain\ValueObject\PasswordResetToken;

class PasswordResetGenerateTokenService implements PasswordResetGenerateTokenServiceInterface
{
    public function generateToken(): PasswordResetToken
    {
        $tokenValue = bin2hex(random_bytes(16));
        $token = new PasswordResetToken($tokenValue);

        return $token;
    }
}