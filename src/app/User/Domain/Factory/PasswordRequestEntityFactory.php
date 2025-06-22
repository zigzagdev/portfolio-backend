<?php

namespace App\User\Domain\Factory;

use App\User\Domain\Entity\PasswordRequestEntity;
use App\Common\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\PasswordResetToken;
use App\Common\Domain\ValueObject\ExpiredAt;
use DateTimeImmutable;

class PasswordRequestEntityFactory
{
    public static function build(array $data): PasswordRequestEntity
    {
        return new PasswordRequestEntity(
            id: $data['id'],
            userId: new UserId($data['user_id']),
            token: new PasswordResetToken($data['token']),
            requestedAt: new DateTimeImmutable($data['requested_at']),
            expiredAt: new ExpiredAt(new DateTimeImmutable($data['expired_at']))
        );
    }
}