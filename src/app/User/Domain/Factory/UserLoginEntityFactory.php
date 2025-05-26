<?php

namespace App\User\Domain\Factory;

use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;

class UserLoginEntityFactory
{
    public static function build(
        array $request,
        PasswordHasherInterface $hasher
    ): UserEntity
    {
        return new UserEntity(
            firstName: '',
            lastName: '',
            email: new Email($request['email']),
            password: Password::fromPlainText(
                $request['password'],
                $hasher
            ),
            bio: null,
            location: null,
            skills: [],
            profileImage: null,
        );
    }
}