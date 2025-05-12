<?php

namespace App\User\Domain\RepositoryInterface;

use App\Models\User;
use App\User\Domain\ValueObject\Email;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function existsByEmail(Email $email): ?bool;
}