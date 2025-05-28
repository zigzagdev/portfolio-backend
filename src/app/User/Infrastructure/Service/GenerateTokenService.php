<?php

namespace App\User\Infrastructure\Service;

use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Service\GenerateTokenInterface;
use App\User\Domain\ValueObject\AuthToken;
use Firebase\JWT\JWT;

class GenerateTokenService implements GenerateTokenInterface
{
    public function __construct(
        private readonly string $secretKey,
        private readonly string $algorithm = 'HS256'
    ) {}
    public function generate(UserEntity $user): string
    {
        $payload = [
            'sub' => $user->getUserId()->getValue(),
            'email' => $user->getEmail()->getValue(),
            'iat' => time(),
            'exp' => time() + 3600,
        ];

        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }
}