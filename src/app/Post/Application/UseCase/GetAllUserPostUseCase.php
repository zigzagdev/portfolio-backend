<?php

namespace App\Post\Application\UseCase;


use App\Common\Application\Dto\Pagination;
use App\Post\Application\QueryServiceInterface\GetPostQueryServiceInterface;

class GetAllUserPostUseCase
{
    public function __construct(
        private readonly GetPostQueryServiceInterface $queryService
    ) {}

    public function handle(
        int $userId,
        int $perPage,
        int $currentPage
    ): Pagination
    {
        $pagination = $this->queryService->getAllUserPosts(
            $userId,
            $perPage,
            $currentPage
        );

        return $pagination;
    }
}
