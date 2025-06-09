<?php

namespace App\User\Domain\Factory;

use App\User\Domain\Entity\UserEntity;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;
use App\Common\Domain\ValueObject\UserId;

class UserEntityFactory
{
    public static function build(array $data, PasswordHasherInterface $hasher): UserEntity
    {
        return new UserEntity(
            id: isset($data['id']) ? new UserId($data['id']) : null,
            firstName: $data['first_name'],
            lastName: $data['last_name'],
            email: new Email($data['email']),
            password: Password::fromPlainText($data['password'], $hasher),
            bio: isset($data['bio']) ? $data['bio'] : null,
            location: isset($data['location']) ? $data['location'] : null,
            skills: is_string($data['skills'])
                ? json_decode($data['skills'], true)
                : (is_array($data['skills']) ? $data['skills'] : []),
            profileImage: isset($data['profile_image']) ? $data['profile_image'] : null
        );
    }
}
