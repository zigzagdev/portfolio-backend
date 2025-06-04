<?php

namespace App\User\Domain\Factory;

use App\User\Application\UseCommand\UpdateUserCommand;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\ValueObject\Email;
use Common\Domain\ValueObjet\UserId;

class UserUpdateEntityFactory
{
    public static function build(
        UpdateUserCommand $command
    ): UserEntity
    {
        $commandArray = $command->toArray();

        return new UserEntity(
            id: new UserId($commandArray['id']),
            firstName: $commandArray['first_name'],
            lastName: $commandArray['last_name'],
            email: new Email($commandArray['email']),
            bio: isset($commandArray['bio']) ? $commandArray['bio'] : null,
            location: isset($commandArray['location']) ? $commandArray['location'] : null,
            skills: isset($commandArray['skills']) ? $commandArray['skills'] : [],
            profileImage: isset($commandArray['profile_image']) ? $commandArray['profile_image'] : null
        );
    }
}