<?php

namespace App\Post\Application\QueryServiceInterface;

use App\Post\Domain\Entity\PostEntityCollection;

interface GetAllUserPostQueryServiceInterface
{
    public function getAllUserPosts(
        int $userId
    ): PostEntityCollection;
}