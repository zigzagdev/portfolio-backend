<?php

namespace App\Post\Presentation\ViewModel;

use App\Post\Application\Dto\EditPostDto;

class EditPostViewModel
{
    public function __construct(
        private readonly EditPostDto $dto,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->dto->getId()->getValue(),
            'user_id' => $this->dto->getUserid()->getValue(),
            'content' => $this->dto->getContent(),
            'media_path' => $this->dto->getMediaPath(),
            'visibility' => strtolower($this->dto->getVisibility()->getValue()->toLabel())
        ];
    }
}