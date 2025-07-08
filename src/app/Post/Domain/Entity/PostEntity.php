<?php

namespace App\Post\Domain\Entity;

use App\Common\Domain\ValueObject\PostId;
use App\Common\Domain\ValueObject\UserId;
use App\Post\Domain\ValueObject\Postvisibility;
use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;

class PostEntity
{
    public function __construct(
        private readonly ?PostId $id,
        private readonly UserId $userId,
        private readonly string $content,
        private readonly ?string $mediaPath,
        private readonly Postvisibility $visibility
    ){}

    public static function build(array $request): self
    {
        return new self(
            id: isset($request['id'])
                ? ($request['id'] instanceof PostId ? $request['id'] : new PostId($request['id']))
                : null,
            userId: $request['userId'] instanceof UserId ? $request['userId'] : new UserId($request['userId']),
            content: $request['content'],
            mediaPath: $request['mediaPath'] ?? null,
            visibility: $request['visibility'] instanceof PostVisibility
                ? $request['visibility']
                : new PostVisibility( PostVisibilityEnum::from(
                    is_int($request['visibility'])
                        ? $request['visibility']
                        : match (strtoupper((string)$request['visibility'])) {
                        'PUBLIC' => PostVisibilityEnum::PRIVATE->value,
                        default => PostVisibilityEnum::PUBLIC->value,
                    }
                ))
        );
    }

    public function getId(): ?PostId
    {
        return $this->id;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getMediaPath(): ?string
    {
        return $this->mediaPath;
    }

    public function getPostVisibility(): Postvisibility
    {
        return $this->visibility;
    }
}