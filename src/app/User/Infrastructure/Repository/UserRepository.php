<?php

namespace App\User\Infrastructure\Repository;

use App\Models\User;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Factory\UserFromModelEntityFactory;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
use InvalidArgumentException;

class UserRepository implements UserRepositoryInterface
{
    public function save(UserEntity $entity): ?UserEntity
    {
        $validatedEmail = $this->existsByEmail($entity->getEmail());

        if ($validatedEmail) {
            throw new InvalidArgumentException('Email is already in use.');
        }

        $model = User::create([
            'first_name' => $entity->getFirstName(),
            'last_name' => $entity->getLastName(),
            'email' => $entity->getEmail()->getValue(),
            'password' => $entity->getPassword()->getHashedPassword(),
            'bio' => $entity->getBio(),
            'location' => $entity->getLocation(),
            'skills' => json_encode($entity->getSkills()),
            'profile_image' => $entity->getProfileImage()
        ]);

        return UserFromModelEntityFactory::buildFromModel($model);
    }

    public function existsByEmail(Email $email): bool
    {
        return User::where('email', $email->getValue())->exists();
    }
}