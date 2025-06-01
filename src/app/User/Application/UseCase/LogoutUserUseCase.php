<?php

namespace App\User\Application\UseCase;

use App\User\Domain\Service\AuthServiceInterface;
use App\User\Domain\Entity\UserEntity;

class LogoutUserUseCase
{
    public function __construct(
        private readonly AuthServiceInterface $authService,
    ) {
    }

    public function handle(
        UserEntity $user
    ): void
    {
        $this->authService->attemptLogout($user);
    }
}