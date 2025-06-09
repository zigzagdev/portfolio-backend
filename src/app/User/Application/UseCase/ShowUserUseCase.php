<?php

namespace App\User\Application\UseCase;

use App\User\Application\Dto\ShowUserDto;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Common\Domain\ValueObject\UserId;

class ShowUserUseCase
{
    public function __construct(
        readonly private UserRepositoryInterface $repository,
    ) {
    }

    public function handle(
        int $userId
    ): ShowUserDto
    {
        $userEntity = $this->repository->findById(new UserId($userId));

        return ShowUserDto::build($userEntity);
    }
}