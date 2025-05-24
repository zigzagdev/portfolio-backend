<?php

namespace App\User\Domain\Factory;

use App\User\Domain\Entity\UserEntity;
use App\Common\Domain\UserId;
use App\User\Domain\ValueObject\Email;

class UserUpdateEntityFactory
{
    public static function build(UserEntity $entity): UserEntity
    {
        return new UserEntity(
            id: new Userid($entity->getUserId()?->getValue()),
            firstName: $entity->getFirstName(),
            lastName: $entity->getLastName(),
            email: new Email($entity->getEmail()->getValue()),
            bio: $entity->getBio() ?? null,
            location: $entity->getLocation() ?? null,
            skills: $entity->getSkills() ?? [],
            profileImage: $entity->getProfileImage() ?? null,
        );
    }
}