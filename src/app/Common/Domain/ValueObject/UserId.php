<?php

namespace App\Common\Domain\ValueObject;

use InvalidArgumentException;

class UserId
{
    private ?int $value;

    public function __construct(?int $value)
    {
        if (!is_null($value) && $value <= 0) {
            throw new InvalidArgumentException('User Id must be a positive integer.');
        }

        $this->value = $value;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }
}