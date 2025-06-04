<?php

namespace App\Common\Domain\Enum;

use InvalidArgumentException;

enum PostVisibility: string
{
    case PUBLIC = 'public';
    case PRIVATE = 'private';

    public static function fromString(string $value): self
    {
        return match ($value) {
            self::PUBLIC->value => self::PUBLIC,
            self::PRIVATE->value => self::PRIVATE,
            default => throw new InvalidArgumentException("Invalid PostVisibility: {$value}"),
        };
    }
}