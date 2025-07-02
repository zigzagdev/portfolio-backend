<?php

namespace App\User\Domain\ValueObject;

use InvalidArgumentException;

final class PasswordResetToken
{
    private readonly string $value;
    public function __construct(string $token)
    {
        if (strlen($token) < 32) {
            throw new InvalidArgumentException('Token must be at least 32 characters long.');
        }

        $this->value = $token;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}