<?php

namespace App\User\Application\UseCase;

use App\User\Application\Dto\LoginUserDto;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use App\User\Domain\Service\GenerateTokenInterface;
use App\User\Domain\Service\AuthServiceInterface;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;
use Exception;

class LoginUserUseCase
{
    public function __construct(
        private readonly AuthServiceInterface $authService,
        private readonly GenerateTokenInterface $generateTokenService,
        private readonly PasswordHasherInterface $passwordHasher

    )
    {}

    public function handle(
        string $email,
        string $password
    ): LoginUserDto
    {
        $buildObjectEmail = new Email($email);
        $buildObjectPassword = Password::fromPlainText(
            $password,
            $this->passwordHasher
        );

        $user = $this->authService->attemptLogin(
            $buildObjectEmail,
            $buildObjectPassword
        );

        if ($user === null) {
            throw new Exception('Invalid credentials');
        }

        $token = $this->generateTokenService->generate($user);

        return LoginUserDto::fromEntity(
            $user,
            $token
        );
    }
}