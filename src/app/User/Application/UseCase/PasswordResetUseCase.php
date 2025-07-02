<?php

namespace App\User\Application\UseCase;

use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Domain\Service\PasswordResetTokenValidatorInterface;
use App\User\Domain\ValueObject\PasswordResetToken;
use App\Common\Domain\ValueObject\UserId;
use Exception;

class PasswordResetUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly PasswordResetTokenValidatorInterface $tokenValidator,
    ) {}

    public function handle(
        string $token,
        string $newPassword
    ): void
    {
        $objectToken = $this->buildObjectToken($token);

        if (!$this->tokenValidator->validate($objectToken->getValue())) {
            throw new Exception('Invalid or expired token.');
        }

        $userId = $this->tokenValidator->getUserIdByToken($objectToken->getValue());
        if (is_null($userId)) {
            throw new Exception('User not found for the given token.');
        }

        $objectUserId = $this->buildObjectUserId($userId);

        $this->repository->resetPassword($objectUserId, $objectToken, $newPassword);
    }

    private function buildObjectToken(string $token): PasswordResetToken
    {
        return new PasswordResetToken($token);
    }

    private function buildObjectUserId($userId): UserId
    {
        return new UserId($userId);
    }
}