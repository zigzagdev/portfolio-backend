<?php

namespace App\User\Domain\RepositoryInterface;

use App\User\Domain\Entity\UserEntity;
use App\User\Domain\ValueObject\Email;
use App\Common\Domain\ValueObject\UserId;

interface UserRepositoryInterface
{
    public function save(UserEntity $entity): ?UserEntity;

    public function existsByEmail(Email $email): bool;

    public function findById(UserId $id): UserEntity;

    public function update(UserEntity $entity): UserEntity;

    public function findByEmail(Email $email): ?UserEntity;
}