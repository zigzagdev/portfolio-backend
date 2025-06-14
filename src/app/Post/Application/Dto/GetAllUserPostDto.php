<?php

namespace App\Post\Application\Dto;

class GetAllUserPostDto
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