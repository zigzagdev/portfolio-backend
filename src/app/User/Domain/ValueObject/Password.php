<?php

namespace App\User\Domain\ValueObject;

use InvalidArgumentException;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use LogicException;

final class Password
{
    private string $hashed;
    private ?string $plain;

    private function __construct(string $hashed, ?string $plain = null)
    {
        $this->hashed = $hashed;
        $this->plain = $plain;
    }

    public static function fromPlainText(string $plain, PasswordHasherInterface $hasher): self
    {
        if (strlen($plain) < 8) {
            throw new InvalidArgumentException('Password must be at least 8 characters.');
        }

        return new self($hasher->hash($plain), $plain);
    }

    public static function fromHashed(string $hashed): self
    {
        return new self($hashed, null);
    }

    public function getHashedPassword(): string
    {
        return $this->hashed;
    }

    public function matches(Password $hashedPassword, PasswordHasherInterface $hasher): bool
    {
        if ($this->plain === null) {
            throw new LogicException('matches() requires plain password');
        }

        return $hasher->check($this->plain, $hashedPassword->getHashedPassword());
    }

    public function __toString(): string
    {
        if ($this->plain === null) {
            throw new LogicException('Cannot convert hashed password to string');
        }

        return $this->plain;
    }
}
