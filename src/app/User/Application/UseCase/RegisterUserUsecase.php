<?php

namespace App\User\Application\UseCase;

use App\User\Application\Dto\RegisterUserDto;
use App\User\Application\UseCommand\RegisterUserCommand;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Domain\Factory\UserEntityFactory;
use App\User\Application\Factory\RegisterUserDtoFactory;

class RegisterUserUsecase
{
    public function __construct(
        readonly private UserRepositoryInterface $repository,
        readonly private PasswordHasherInterface $passwordHasher,
    ) {
    }

    public function handle(
        RegisterUserCommand $command
    ): RegisterUserDto
    {
        $userEntity = UserEntityFactory::build(
            $command->toArray(),
            $this->passwordHasher,
        );

        $result = $this->repository->save($userEntity);

        return RegisterUserDtoFactory::build($result);
    }
}