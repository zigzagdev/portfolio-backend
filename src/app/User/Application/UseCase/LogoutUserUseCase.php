<?php

namespace App\User\Application\UseCase;

use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Domain\Service\AuthServiceInterface;
use Common\Domain\ValueObject\UserId;

class LogoutUserUseCase
{
    public function __construct(
        private readonly AuthServiceInterface $authService,
        private readonly UserRepositoryInterface $repositoryInterface
    ) {
    }

    public function handle(
        int $userId
    ): void
    {
        $targetUser = $this->repositoryInterface->findById(new UserId($userId));

        $this->authService->attemptLogout($targetUser);
    }
}