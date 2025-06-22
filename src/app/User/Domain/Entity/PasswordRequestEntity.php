<?php

namespace App\User\Domain\Entity;

use App\Common\Domain\ValueObject\ExpiredAt;
use App\Common\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\PasswordResetToken;
use DateTimeImmutable;
use InvalidArgumentException;

class PasswordRequestEntity
{
    public function __construct(
        private readonly int $id,
        private readonly UserId $userId,
        private readonly PasswordResetToken $token,
        private readonly DateTimeImmutable $requestedAt,
        private readonly ExpiredAt $expiredAt
    )
    {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getToken(): PasswordResetToken
    {
        return $this->token;
    }

    public function getRequestedAt(): DateTimeImmutable
    {
        return $this->requestedAt;
    }

    public function getExpiredAt(): ExpiredAt
    {
        return $this->expiredAt;
    }

    public function isExpired(?DateTimeImmutable $now = null): bool
    {
        return $this->expiredAt->isExpired($now);
    }
}