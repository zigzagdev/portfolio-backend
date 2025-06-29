<?php

namespace App\User\Application\UseCase;

use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Domain\Service\PasswordResetGenerateTokenServiceInterface;
use App\User\Domain\Service\PasswordResetNotificationServiceInterface;
use App\User\Domain\Service\ThrottlePasswordResetRequestServiceInterface;
use App\User\Domain\ValueObject\Email;
use InvalidArgumentException;

class RequestUserPasswordResetUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly PasswordResetGenerateTokenServiceInterface $tokenService,
        private readonly PasswordResetNotificationServiceInterface $notificationService,
        private readonly ThrottlePasswordResetRequestServiceInterface $throttleService,
    ) {}

    public function handle(string $email): void
    {
      $user = $this->repository->findByEmail($this->buildObjectEmail($email));

        if (!$user) {
            throw new InvalidArgumentException('User not found');
        }

        $this->throttleService->checkThrottling($user);

        $token = $this->tokenService->generateToken();

        $this->repository->savePasswordResetToken($user->getUserId(), $token);

        $this->notificationService->sendResetLink($user, $token->getValue());
    }

    private function buildObjectEmail(string $email): Email
    {
        return new Email($email);
    }
}