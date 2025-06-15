<?php

namespace App\Post\Application\UseCase;

use App\Post\Domain\Entity\PostEntity;
use App\Post\Application\QueryServiceInterface\GetPostQueryServiceInterface;
use App\Common\Domain\ValueObject\PostId;
use App\Common\Domain\ValueObject\UserId;
use InvalidArgumentException;

class GetUserEachPostUseCase
{
    public function __construct(
        private readonly GetPostQueryServiceInterface $queryService
    ) {}

    public function handle(
        int $userId,
        int $postId
    ): ?PostEntity{
        $post = $this->queryService->getEachUserPost(
            $this->buildObjectUserId($userId),
            $this->buildObjectPostId($postId)
        );

        return new PostEntity(
            $post->getId(),
            $post->getUserId(),
            $post->getContent() ?? '',
            $post->getMediaPath(),
            $post->getPostVisibility()
        );
    }

    private function buildObjectUserId(
        int $userId
    ): UserId
    {
        if ($userId <= 0) {
            throw new InvalidArgumentException('User ID must be a positive integer.');
        }

        return new UserId($userId);
    }

    private function buildObjectPostId(
        int $postId
    ): PostId
    {
        if ($postId <= 0) {
            throw new InvalidArgumentException('Post ID must be a positive integer.');
        }

        return new PostId($postId);
    }
}