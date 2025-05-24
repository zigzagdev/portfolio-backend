<?php

namespace App\User\Domain\Factory;

use App\User\Domain\Entity\UserEntity;
use App\Common\Domain\UserId;
use App\User\Domain\ValueObject\Email;

class UserUpdateEntityFactory
{
    public static function build(array $request): UserEntity
    {
        return new UserEntity(
            id: new Userid($request['id']),
            firstName: $request['first_name'],
            lastName: $request['last_name'],
            email: new Email($request['email']),
            bio: $request['bio'] ?? null,
            location: $request['location'] ?? null,
            skills: is_string($request['skills'])
                ? json_decode($request['skills'], true)
                : (is_array($request['skills']) ? $request['skills'] : []),
            profileImage: $request['profile_image'] ?? null
        );
    }
}