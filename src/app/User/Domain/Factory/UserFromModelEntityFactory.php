<?php

namespace App\User\Domain\Factory;

use App\Models\User;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;
use Common\Domain\ValueObject\UserId;

class UserFromModelEntityFactory
{
    public static function buildFromModel(User $model): UserEntity
    {
        return new UserEntity(
            id: new UserId($model->id),
            firstName: $model->first_name,
            lastName: $model->last_name,
            email: new Email($model->email),
            password: Password::fromHashed($model->password),
            bio: isset($model->bio) ? $model->bio : null,
            location: isset($model->location) ? $model->location : null,
            skills: is_string($model->skills)
                ? json_decode($model->skills, true)
                : (is_array($model->skills) ? $model->skills : []),
            profileImage: isset($model->profile_image) ? $model->profile_image : null
        );
    }
}