<?php

namespace App\Post\Domain\Entity;

class PostEntityCollection
{
    /**
     * @param PostEntity[] $posts
     */
    public function __construct(
        private array $posts = []
    ) {}

    /**
     * Builds a PostEntityCollection from an array of post data.
     *
     * @param array<> $posts
     * @return PostEntityCollection
     */
    public static function build(array $posts): self
    {
        $postEntities = array_map(
            fn($post) => PostEntity::build($post),
            $posts
        );

        return new self($postEntities);
    }

    /**
     * @return array<int, array{id: int|null, userId: int, content: string, mediaPath: string|null, visibility: string}>
     */
    public function toArray(): array
    {
        return array_map(
            fn(PostEntity $post) => [
                'id' => $post->getId()?->getValue(),
                'userId' => $post->getUserId()->getValue(),
                'content' => $post->getContent(),
                'mediaPath' => $post->getMediaPath(),
                'visibility' => $post->getPostVisibility()->getValue()
            ],
            $this->posts
        );
    }

    /**
     * @return PostEntity[]
     */
    public function getPosts(): array
    {
        return $this->posts;
    }


    /**
     * @param PostEntity $post
     */
    public function addPost(PostEntity $post): void
    {
        $this->posts[] = $post;
    }
}