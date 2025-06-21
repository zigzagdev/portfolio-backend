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
            'userId' => $this->dto->getUserid()->getValue(),
            'content' => $this->dto->getContent(),
            'mediaPath' => $this->dto->getMediaPath(),
            'visibility' => $this->dto->getVisibility()->getValue()->toLabel(),
        ];
    }
}