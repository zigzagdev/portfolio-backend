<?php

namespace App\Post\Application\UseCase;

use App\Common\Domain\ValueObject\UserId;
use App\Common\Application\Dto\Pagination;
use App\Post\Application\QueryServiceInterface\GetAllUserPostQueryServiceInterface;

class GetAllUserPostUseCase
{
    public function __construct(
        private readonly GetAllUserPostQueryServiceInterface $queryService
    ) {}

    public function handle(
        int $userId,
        int $perPage,
        int $currentPage
    ): Pagination
    {
        return $this->queryService->getAllUserPosts(
            $userId,
            $perPage,
            $currentPage
        );
    }
}