<?php

namespace App\User\Domain\RepositoryInterface;

interface PasswordHasherInterface
{
    public function hash(string $plain): string;
}