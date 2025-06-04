<?php

namespace App\Post\Domain\ValueObject;

use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;
use InvalidArgumentException;

class Postvisibility
{
    public function __construct(
        private readonly PostVisibilityEnum $enum
    ) {
        if (!in_array($this->enum->value, [PostVisibilityEnum::PUBLIC->value, PostVisibilityEnum::PRIVATE->value])) {
            throw new InvalidArgumentException('Invalid post visibility value.');
        }
    }

    public function getValue(): PostVisibilityEnum
    {
        return $this->enum;
    }

    public function getStringValue(): string
    {
        return $this->enum->value;
    }
}