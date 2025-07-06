<?php

namespace App\Post\Application\Dto;

use App\Post\Application\Dto\GetUserEachPostDto as PostDto;

class GetAllUserPostDtoCollection
{
    /**
     * @param PostDto[] $posts
     */
    public function __construct(
        public readonly array $posts
    ) {}

    public static function build(array $items): self
    {
        $postDtos = array_map(
            fn($item) => PostDto::build($item),
            $items
        );

        return new self($postDtos);
    }

    /**
     * Convert the collection to an array of DTOs.
     *
     * @return PostDto[]
     */
    public function getPosts(): array
    {
        return $this->posts;
    }
}