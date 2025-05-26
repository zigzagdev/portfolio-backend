<?php

namespace App\User\Domain\ValueObject;

use InvalidArgumentException;

final class AuthToken
{
    public function __construct(
        private string $value,
        private string $type = 'Bearer'
    ) {}

    public function getValue(): string
    {
        return $this->value;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function __toString(): string
    {
        return "{$this->type} {$this->value}";
    }

}