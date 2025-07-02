<?php

namespace App\User\Domain\Service;

use App\User\Domain\ValueObject\Email;

interface PasswordResetRequestLimitationServiceInterface
{
    public function canRequestReset(Email $email): bool;
}