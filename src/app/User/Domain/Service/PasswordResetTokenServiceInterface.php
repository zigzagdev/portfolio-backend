<?php

namespace App\User\Domain\Service;

use App\Models\User;

interface PasswordResetTokenServiceInterface
{
    public function generateToken(User $user): string;
}