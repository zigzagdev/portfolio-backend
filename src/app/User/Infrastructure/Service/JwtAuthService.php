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

        $inputPassword = Password::fromPlainText($password->__toString(), $this->passwordHasher);
        $storedPassword = Password::fromHashed($user->getPassword()?->getHashedPassword());

        if (!$inputPassword->matches($storedPassword, $this->passwordHasher)) {
            return null;
        }

        return $user;
    }

    public function attemptLogout(UserEntity $user): void
    {
        // In a JWT-based system, logout is typically handled by removing the token from the client side.
        // However, if you want to implement server-side token invalidation, you can do so here.
        // For example, you could maintain a blacklist of tokens or change the user's password to invalidate existing tokens.
        // This is a placeholder for such logic.
    }
}