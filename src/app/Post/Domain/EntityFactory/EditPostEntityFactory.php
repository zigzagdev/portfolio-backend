<?php

namespace App\Post\Domain\EntityFactory;

use App\Common\Domain\ValueObject\UserId;
use App\Common\Domain\ValueObject\PostId;
use App\Post\Domain\ValueObject\PostVisibility;
use App\Post\Domain\Entity\PostEntity;
use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;
use InvalidArgumentException;

class EditPostEntityFactory
{
    public static function build(array $request): PostEntity
    {
        self::validate($request);

        return PostEntity::build([
            'id' => isset($request['id']) ? new PostId($request['id']) : null,
            'userId' => new UserId($request['userId']),
            'content' => $request['content'],
            'mediaPath' => $request['mediaPath'] ?? null,
            'visibility' => new PostVisibility(PostVisibilityEnum::fromString($request['visibility']))
        ]);
    }

    public static function validate(array $request): void
    {
        if (empty($request['id'])) {
            throw new InvalidArgumentException('Post ID is required.');
        }

        if (empty($request['userId'])) {
            throw new InvalidArgumentException('User ID is required.');
        }

        if (empty($request['content'])) {
            throw new InvalidArgumentException('Content is required.');
        }

        if (!isset($request['visibility']) || !in_array($request['visibility'], ['public', 'private'])) {
            throw new InvalidArgumentException('Visibility must be either "public" or "private".');
        }
    }
}