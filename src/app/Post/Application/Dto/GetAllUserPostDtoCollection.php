<?php

namespace App\Post\Application\Dto;

use App\Post\Application\Dto\GetUserEachPostDto;

class GetAllUserPostDtoCollection
{
    public function __construct(
        public readonly array $posts
    ) {}

    public static function build(array $collection): self
    {
        $postDtos = array_map(
            fn($entity) => GetUserEachPostDto::buildFromEntity($entity),
            $collection
        );

        return new self($postDtos);
    }

    public function getPosts(): array
    {
        return $this->posts;
    }
}