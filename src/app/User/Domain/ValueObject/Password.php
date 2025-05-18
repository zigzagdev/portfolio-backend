<?php

namespace App\User\Domain\ValueObject;

use InvalidArgumentException;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;

final class Password
{
    private string $value;

    private function __construct(string $hashed)
    {
        $this->value = $hashed;
    }

    public static function fromPlainText(string $plain, PasswordHasherInterface $hasher): self
    {
        if (strlen($plain) < 8) {
            throw new InvalidArgumentException('Password must be at least 8 characters.');
        }

        return new self($hasher->hash($plain));
    }

    public static function fromHashed(string $hashed): self
    {
        return new self($hashed);
    }

    public function getHashedPassword(): string
    {
        return $this->value;
    }
}