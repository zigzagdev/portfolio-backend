<?php

namespace App\User\Infrastructure\Service;

use App\User\Domain\Service\ThrottlePasswordResetRequestServiceInterface;
use App\Models\User;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class ThrottlePasswordResetRequestService implements ThrottlePasswordResetRequestServiceInterface
{
    private const MAX_REQUESTS = 5;
    private const TIME_FRAME = 3600;

    public function checkThrottling(User $user): void
    {
        if (!$user->passwordResetRequests()) {
            throw new InvalidArgumentException('User does not have password reset requests.');
        }

        $requests = $user
            ->passwordResetRequests()
            ->where('created_at', '>=', now()->subSeconds(self::TIME_FRAME))
            ->where('user_id', $user->id)
            ->count();

        if ($requests >= self::MAX_REQUESTS) {
            throw new TooManyRequestsHttpException('Too many password reset requests. Please try again later.');
        }
    }
}