<?php

namespace App\Post\Infrastructure\QueryService;

use App\Models\Post;
use App\Post\Application\QueryServiceInterface\GetAllUserPostQueryServiceInterface;
use App\Post\Domain\Entity\PostEntityCollection;
use App\Post\Domain\EntityFactory\PostFromModelEntityFactory;

class GetAllUserPostQueryService implements GetAllUserPostQueryServiceInterface
{
    public function __construct(
        private readonly Post $post
    ) {}

    public function getAllUserPosts(int $userId): PostEntityCollection
    {
        $userPosts = $this->post->where('user_id', $userId)->get();

        $postEntities = $userPosts->map(function ($post) {
            return PostFromModelEntityFactory::build($post->toArray());
        });

        return new PostEntityCollection($postEntities->all());
    }
}