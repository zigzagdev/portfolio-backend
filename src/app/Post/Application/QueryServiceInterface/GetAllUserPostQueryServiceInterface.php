<?php

namespace App\Post\Application\QueryServiceInterface;

use App\Post\Domain\Entity\PostEntity;
use App\Common\Application\Dto\Pagination as PaginationDto;

interface GetAllUserPostQueryServiceInterface
{
    public function getAllUserPosts(
        int $userId,
        int $perPage,
        int $currentPage
    ): PaginationDto;

    public function getEachUserPost(
        int $userId,
        int $postId
    ): ?PostEntity;
}