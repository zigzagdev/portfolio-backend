<?php

namespace App\User\Domain\Service;

interface PasswordResetTokenValidatorInterface
{
    public function validate(
        string $userId,
        string $token,
    ): void;
}