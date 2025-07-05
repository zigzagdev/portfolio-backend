<?php

namespace App\Post\Application\UseCase;

use App\Common\Application\Dto\Pagination as PaginationDto;
use App\Post\Application\QueryServiceInterface\GetPostQueryServiceInterface;

class GetOthersAllPostsUseCase
{
    public function __construct(
       private readonly  GetPostQueryServiceInterface $queryService
    ){}

    public function handle(
        int $userId,
        int $perPage,
        int $currentPage
    ): PaginationDto {

        return $this->queryService->getOthersAllPosts(
            userId: $userId,
            perPage: $perPage,
            currentPage: $currentPage
        );
    }
}