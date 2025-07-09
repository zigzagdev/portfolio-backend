<?php

namespace App\Post\Infrastructure\Repository;

use App\Post\Domain\RepositoryInterface\PostRepositoryInterface;
use App\Models\Post;
use App\Post\Domain\Entity\PostEntity;
use App\Post\Domain\EntityFactory\PostFromModelEntityFactory;
use Exception;

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

    public function editById(PostEntity $entity): PostEntity
    {
        $targetPost = $this->post
            ->where('id', $entity->getId()?->getValue())
            ->where('user_id', $entity->getUserId()->getValue())
            ->first();

        if (is_null($targetPost)) {
            throw new Exception('The post could not be found or edited.');
        }

        $targetPost->fill([
            'content' => $entity->getContent(),
            'user_id' => $entity->getUserId()->getValue(),
            'media_path' => $entity->getMediaPath(),
            'visibility' => $entity->getPostVisibility()->getValue(),
        ])->save();

        return PostFromModelEntityFactory::build($targetPost->toArray());
    }
}