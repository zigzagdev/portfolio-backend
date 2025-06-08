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

    public function toInt(): int
    {
        return match ($this) {
            self::PUBLIC => 1,
            self::PRIVATE => 2,
        };
    }

    public function toLabel(): string
    {
        return match ($this) {
            self::PUBLIC => 'Public',
            self::PRIVATE => 'Private',
        };
    }
}