<?php

namespace App\Post\Infrastructure\Repository;

use App\Post\Domain\RepositoryInterface\PostRepositoryInterface;
use App\Models\Post;
use App\Post\Domain\Entity\PostEntity;
use App\Post\Domain\EntityFactory\PostFromModelEntityFactory;

class PostRepository implements PostRepositoryInterface
{
    public function __construct(
        private readonly Post $post
    ) {}

    public function save(PostEntity $entity): ?PostEntity
    {
        $newPost = $this->post->create([
            'content' => $entity->getContent(),
            'user_id' => $entity->getUserId()->getValue(),
            'media_path' => $entity->getMediaPath(),
            'visibility' => $entity->getPostVisibility()->getValue(),
        ])->toArray();

        return PostFromModelEntityFactory::build($newPost);
    }
}