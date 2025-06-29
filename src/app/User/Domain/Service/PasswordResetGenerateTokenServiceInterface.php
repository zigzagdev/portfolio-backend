<?php

namespace App\User\Domain\Service;

use App\Models\User;
use App\User\Domain\ValueObject\PasswordResetToken;

interface PasswordResetGenerateTokenServiceInterface
{
    public function generateToken(): PasswordResetToken;
}