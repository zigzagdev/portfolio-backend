<?php

namespace App\Post\Presentation\ViewModel;

use App\Post\Application\Dto\GetUserEachPostDto;

class GetAllUserPostViewModel
{
    public function __construct(
        private int $id,
        private int $userId,
        private string $content,
        private ?string $mediaPath,
        private string $visibility
    ) {}

    public static function build(GetUserEachPostDto $data): self
    {
        $dtoArray = $data->toArray();
        return new self(
            id: $dtoArray['id'],
            userId: $dtoArray['userId'],
            content: $dtoArray['content'],
            mediaPath: $dtoArray['mediaPath'] ?? null,
            visibility: $dtoArray['visibility']
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