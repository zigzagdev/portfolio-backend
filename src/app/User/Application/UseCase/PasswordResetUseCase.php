<?php

namespace App\User\Application\UseCase;

use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Domain\Service\PasswordResetTokenValidatorInterface;
use App\User\Domain\ValueObject\PasswordResetToken;
use App\Common\Domain\ValueObject\UserId;

class PasswordResetUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly PasswordResetTokenValidatorInterface $tokenValidator,
    ) {}

    public function handle(
        string $userId,
        string $token,
        string $newPassword
    ): void
    {
        $objectUserId = $this->buildObjectUserId($userId);
        $objectToken = $this->buildObjectToken($token);


        $this->tokenValidator
            ->validate(
                $objectUserId->getValue(),
                $objectToken->getValue()
            );

        $this->repository
            ->resetPassword(
                $objectUserId,
                $objectToken,
                $newPassword
            );
    }

    private function buildObjectUserId(string $userId): UserId
    {
        return new UserId($userId);
    }

    private function buildObjectToken(string $token): PasswordResetToken
    {
        return new PasswordResetToken($token);
    }
}