<?php

namespace App\Post\Application\QueryServiceInterface;

use App\Post\Domain\Entity\PostEntity;
use App\Common\Application\Dto\Pagination as PaginationDto;
use App\Common\Domain\ValueObject\PostId;
use App\Common\Domain\ValueObject\UserId;

interface GetPostQueryServiceInterface
{
    public function getAllUserPosts(
        int $userId,
        int $perPage,
        int $currentPage
    ): PaginationDto;

    public function getEachUserPost(
        UserId $userId,
        PostId $postId
    ): ?PostEntity;

    public function getOthersAllPosts(
        int $userId,
        int $perPage,
        int $currentPage
    ): ?PaginationDto;

//    public function getOneAllPosts(
//        int $userId,
//        int $targetUserId,
//        int $perPage,
//        int $currentPage
//    ): PaginationDto;
}