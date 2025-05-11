<?php

namespace App\User\Domain\Factory;

use App\User\Domain\Entity\UserEntity;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;
use App\Common\Domain\UserId;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;

class UserEntityFactory
{
    public static function build(array $data, PasswordHasherInterface $hasher): UserEntity
    {
        return new UserEntity(
            id: new UserId($data['id']) ?? null,
            firstName: $data['first_name'],
            lastName: $data['last_name'],
            email: new Email($data['email']),
            password: Password::fromPlainText($data['password'], $hasher),
            bio: $data['bio'] ?? null,
            location: $data['location'] ?? null,
            skills: $data['skills'] ?? [],
            profileImage: $data['profile_image'] ?? null
        );
    }
}
