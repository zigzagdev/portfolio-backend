<?php

namespace App\User\Infrastructure;

use Illuminate\Support\Facades\Hash;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;

class BcryptPasswordHasher implements PasswordHasherInterface
{
    public function hash(string $plain): string
    {
        return Hash::make($plain);
    }
}