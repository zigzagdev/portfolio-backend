<?php

namespace App\User\Infrastructure\Service;

use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Factory\UserFromModelEntityFactory;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Domain\Service\AuthServiceInterface;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;
use Illuminate\Support\Facades\Hash;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;

class JwtAuthService implements AuthServiceInterface
{
    public function __construct(
        private readonly  UserRepositoryInterface $userRepository,
        private readonly PasswordHasherInterface $passwordHasher,
    ) {}

    public function attemptLogin(Email $email, Password $password): ?UserEntity
    {
        $existUser = $this->userRepository->existsByEmail($email);

        if (!$existUser) {
            return null;
        }

        $user = $this->userRepository->findByEmail($email);

        if ($user === null) {
            return null;
        }

        if (!Hash::check($password->getHashedPassword(), $user->getPassword()?->getHashedPassword())) {
            return null;
        }

        return $user;
    }
}