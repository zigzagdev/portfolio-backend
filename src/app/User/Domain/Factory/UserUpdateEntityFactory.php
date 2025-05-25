<?php

namespace App\User\Domain\Factory;

use App\User\Application\UseCommand\UpdateUserCommand;
use App\User\Domain\Entity\UserEntity;
use App\Common\Domain\UserId;
use App\User\Domain\ValueObject\Email;

class UserUpdateEntityFactory
{
    public static function build(
        UpdateUserCommand $command
    ): UserEntity
    {
        return new UserEntity(
            id: new Userid($command->getId()),
            firstName: $command->getFirstName(),
            lastName: $command->getLastName(),
            email: new Email($command->getEmail()),
            bio: $command->getBio(),
            location: $command->getLocation(),
            skills: $command->getSkills(),
            profileImage: $command->getProfileImage()
        );
    }
}