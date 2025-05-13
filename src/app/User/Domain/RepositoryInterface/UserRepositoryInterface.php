<?php

namespace App\User\Domain\RepositoryInterface;

use App\Models\User;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\ValueObject\Email;

interface UserRepositoryInterface
{
    public function save(UserEntity $entity): ?UserEntity;

    public function existsByEmail(Email $email): bool;
}