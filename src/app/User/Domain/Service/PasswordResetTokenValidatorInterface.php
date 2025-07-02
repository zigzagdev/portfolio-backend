<?php

namespace App\User\Domain\Service;

interface PasswordResetTokenValidatorInterface
{
    public function validate(
        string $token
    ): bool;

    public function getUserIdByToken(
        string $token
    ): ?int;
}