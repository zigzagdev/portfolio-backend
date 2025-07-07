<?php

namespace App\Post\Application\Dto;

use App\Post\Domain\Entity\PostEntity;

class GetUserEachPostDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $userId,
        public readonly string $content,
        public readonly ?string $mediaPath,
        public readonly string $visibility,
    ) {}

    public static function build(array $data): self
    {
        return new self(
            id: $data['id'],
            userId: $data['userId'],
            content: $data['content'],
            mediaPath: $data['mediaPath'] ?? null,
            visibility: $data['visibility'],
        );
    }

    public static function buildFromEntity(PostEntity $entity): self
    {
        return new self(
            id: $entity->getId()->getValue(),
            userId: $entity->getUserId()->getValue(),
            content: $entity->getContent(),
            mediaPath: $entity->getMediaPath(),
            visibility: $entity->getPostVisibility()->getStringValue()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'content' => $this->content,
            'mediaPath' => $this->mediaPath,
            'visibility' => $this->visibility
        ];
    }
}