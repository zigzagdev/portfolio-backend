<?php

namespace App\User\Application\Factory;

use App\User\Domain\Entity\UserEntity;
use App\User\Domain\ValueObject\Email;
use App\Common\Domain\UserId;
use App\User\Application\Dto\RegisterUserDto;

class RegisterUserDtoFactory
{
    public static function build(UserEntity $entity): RegisterUserDto
    {
        return new RegisterUserDto(
            new UserId($entity->getUserId()?->getValue()),
            $entity->getFirstName(),
            $entity->getLastName(),
            new Email($entity->getEmail()->getValue()),
            $entity->getBio(),
            $entity->getLocation(),
            $entity->getSkills(),
            $entity->getProfileImage(),
        );
    }
}