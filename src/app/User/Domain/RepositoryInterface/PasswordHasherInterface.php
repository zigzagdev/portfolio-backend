<?php

namespace App\User\Domain\RepositoryInterface;

interface PasswordHasherInterface
{
    public function hash(string $plain): string;

    public function check(string $plain, string $hashed): bool;
}