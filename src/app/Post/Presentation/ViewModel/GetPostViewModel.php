<?php

namespace App\Post\Presentation\ViewModel;

use App\Post\Application\Dto\GetUserEachPostDto;

class GetPostViewModel
{
    public function __construct(
        public readonly int $id,
        public readonly int $userId,
        public readonly string $content,
        public readonly ?string $mediaPath,
        public readonly string $visibility
    ) {}

    public static function build(GetUserEachPostDto $dto): self
    {
        return new self(
            id: $dto->id,
            userId: $dto->userId,
            content: $dto->content,
            mediaPath: $dto->mediaPath,
            visibility: $dto->visibility
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
