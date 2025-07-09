<?php

namespace App\Common\Domain\ValueObject;

use DateTimeImmutable;
use InvalidArgumentException;


class ExpiredAt
{
    private DateTimeImmutable $value;

    public function __construct(DateTimeImmutable $value)
    {
        if ($value < new DateTimeImmutable()) {
            throw new InvalidArgumentException('Expiration date must be in the future.');
        }

        $this->value = $value;
    }

    public function getValue(): DateTimeImmutable
    {
        return $this->value;
    }

    public function isExpired(?DateTimeImmutable $now = null): bool
    {
        $now ??= new DateTimeImmutable();
        return $now > $this->value;
    }
}