<?php

namespace App\Post\Application\Dto;

use App\Common\Domain\ValueObject\PostId;
use App\Common\Domain\ValueObject\UserId;
use App\Post\Domain\Entity\PostEntity;
use App\Post\Domain\ValueObject\PostVisibility;
use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;

class CreatePostDto
{
    public function __construct(
        public readonly PostEntity $entity,
    ) {
    }

    public function getId(): PostId
    {
        return $this->entity->getId();
    }

    public function getUserid(): UserId
    {
        return $this->entity->getUserId();
    }

    public function getContent(): string
    {
        return $this->entity->getContent();
    }

    public function getMediaPath(): ?string
    {
        return $this->entity->getMediaPath();
    }

    public function getVisibility(): PostVisibility
    {
        return $this->entity->getPostVisibility();
    }

    public function toArray(): array
    {
        return [
            'userId' => $this->getUserid()->getValue(),
            'content' => $this->getContent(),
            'mediaPath' => $this->getMediaPath(),
            'visibility' => $this->getVisibility()->getValue(),
        ];
    }

    public static function build(PostEntity $entity): self
    {
        return new self($entity);
    }
}