<?php

namespace App\Post\Domain\EntityFactory;

use App\Common\Domain\ValueObject\UserId;
use App\Common\Domain\ValueObject\PostId;
use App\Post\Domain\ValueObject\Postvisibility;
use App\Post\Domain\Entity\PostEntity;
use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;

class PostFromModelEntityFactory
{
    public static function build(array $request): PostEntity
    {
        return PostEntity::build([
            'id' => new PostId($request['id']),
            'userId' => new UserId($request['userId']),
            'content' => $request['content'],
            'mediaPath' => $request['mediaPath'] ?? null,
            'visibility' => new PostVisibility(
                PostVisibilityEnum::fromString($request['visibility'])
            ),
        ]);
    }
}