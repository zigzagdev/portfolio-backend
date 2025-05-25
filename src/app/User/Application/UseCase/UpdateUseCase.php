<?php

namespace App\User\Application\UseCase;

use App\User\Application\Dto\UpdateUserDto;
use App\User\Application\UseCommand\UpdateUserCommand;
use App\User\Domain\Factory\UserUpdateEntityFactory;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;

class UpdateUseCase
{
    public function __construct(
        readonly private UserRepositoryInterface $repository,
    ) {
    }

    public function handle(
        UpdateUserCommand $command
    ): UpdateUserDto
    {
        $entity = UserUpdateEntityFactory::build(
            $command
        );

        $result = $this->repository->update($entity);

        return UpdateUserDto::build($result);
    }
}