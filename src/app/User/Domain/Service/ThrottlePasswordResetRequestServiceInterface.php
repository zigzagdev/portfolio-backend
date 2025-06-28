<?php

namespace App\User\Domain\Service;

use App\Models\User;

interface ThrottlePasswordResetRequestServiceInterface
{
    public function checkThrottling(User $user): void;
}