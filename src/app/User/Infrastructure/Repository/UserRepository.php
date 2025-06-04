<?php

namespace App\User\Infrastructure\Repository;

use App\Common\Domain\UserId;
use App\Models\User;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Factory\UserFromModelEntityFactory;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use LogicException;
use Exception;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly PasswordHasherInterface $hasher,
        private readonly User $user
    ) {}

    public function save(UserEntity $entity): ?UserEntity
    {
        $hashed = $entity->getPassword()
            ? $entity->getPassword()->getHashedPassword()
            : throw new LogicException('Password is missing.');

        $model = $this->user->create([
            'first_name' => $entity->getFirstName(),
            'last_name' => $entity->getLastName(),
            'email' => $entity->getEmail()->getValue(),
            'password' => $hashed,
            'bio' => $entity->getBio(),
            'location' => $entity->getLocation(),
            'skills' => $entity->getSkills(),
            'profile_image' => $entity->getProfileImage()
        ]);

        return UserFromModelEntityFactory::buildFromModel($model);
    }

    public function existsByEmail(Email $email): bool
    {
        return $this->user->where('email', $email->getValue())->exists();
    }

    public function findByEmail(Email $email): ?UserEntity
    {
        $findUser = $this->user->where('email', $email->getValue())->first();

        if ($findUser === null) {
            return null;
        }

        return UserFromModelEntityFactory::buildFromModel($findUser);
    }

    public function findById(UserId $id): UserEntity
    {
        $findUser = $this->user->find($id->getValue());

        if ($findUser === null) {
            throw new Exception('User not found');
        }

        return UserFromModelEntityFactory::buildFromModel($findUser);
    }

    public function update(
        UserEntity $entity
    ): UserEntity
    {
        $targetUser = $this->user->find($entity->getUserId()->getValue());

        if ($targetUser === null) {
            throw new Exception('User not found');
        }

        $targetUser->update(
            [
                'id' => $targetUser->id,
                'first_name' => $entity->getFirstName(),
                'last_name' => $entity->getLastName(),
                'email' => $entity->getEmail()->getValue(),
                'bio' => $entity->getBio(),
                'location' => $entity->getLocation(),
                'skills' => json_encode($entity->getSkills()),
                'profile_image' => $entity->getProfileImage()
            ]
        );

        return UserFromModelEntityFactory::buildFromModel($this->user->find($entity->getUserId()->getValue()));
    }
}
