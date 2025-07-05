<?php

namespace App\Common\Domain\Enum;

enum PostVisibility: int
{
    case PUBLIC = 0;
    case PRIVATE = 1;

    public function toLabel(): string
    {
        return match ($this) {
            self::PUBLIC => 'Public',
            self::PRIVATE => 'Private',
        };
    }
}