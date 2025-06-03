<?php

namespace App\User\Application\Dto;

use App\User\Domain\Entity\UserEntity;
use App\User\Domain\ValueObject\AuthToken;
use App\User\Domain\ValueObject\Email;
use Common\Domain\ValueObject\UserId;

class LoginUserDto
{
    public function __construct(
        public readonly UserEntity $user,
        public readonly AuthToken $token,
    ) {
    }


    public static function fromEntity(
        UserEntity $user,
        AuthToken $token
    ): self
    {
        return new self(
            user: $user,
            token: $token,
        );
    }

    public function getUserId(): UserId
    {
        return $this->user->getUserId();
    }

    public function getEmail(): Email
    {
        return $this->user->getEmail();
    }

    public function getToken(): AuthToken
    {
        return $this->token;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->user->getUserId()->getValue(),
            'email' => $this->user->getEmail()->getValue(),
            'token' => $this->token->getValue(),
        ];
    }
}