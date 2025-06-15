<?php

namespace App\Post\Application\UseCase;

use App\Post\Application\Dto\GetUserEachPostDto;
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
    ): GetUserEachPostDto
    {
        $objectUserId = $this->buildObjectUserId($userId);
        $objectPostId = $this->buildObjectPostId($postId);

        $post = $this->queryService->getEachUserPost(
            userId: $objectUserId,
            postId: $objectPostId
        );

        if ($post === null) {
            throw new InvalidArgumentException('Post not found for the given user.');
        }

        return new GetUserEachPostDto(
            id: $post->getId()->getValue(),
            userId: $post->getUserId()->getValue(),
            content: $post->getContent(),
            mediaPath: $post->getMediaPath(),
            visibility: $post->getPostVisibility()->getStringValue()
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