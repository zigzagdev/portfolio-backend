<?php

namespace App\Common\Domain;

use InvalidArgumentException;

class Userid
{
    private ?int $value;

    public function __construct(?int $value)
    {
        if (!is_null($value) && $value <= 0) {
            throw new InvalidArgumentException('User ID must be a positive integer.');
        }

        $this->value = $value;
    }

    public function getUserId(): ?int
    {
        return $this->value;
    }
}