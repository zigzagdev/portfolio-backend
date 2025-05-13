<?php

namespace App\User\Infrastructure\Repository;

use App\Models\User;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Factory\UserFromModelEntityFactory;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use LogicException;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly PasswordHasherInterface $hasher
    ) {}

    public function save(UserEntity $entity): ?UserEntity
    {
        $hashed = $entity->getPassword()
            ? $entity->getPassword()->getHashedPassword()
            : throw new LogicException('Password is missing.');

        $model = User::create([
            'first_name' => $entity->getFirstName(),
            'last_name' => $entity->getLastName(),
            'email' => $entity->getEmail()->getValue(),
            'password' => $hashed,
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