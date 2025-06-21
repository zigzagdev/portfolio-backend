<?php

namespace App\Post\Application\UseCase;

use App\Post\Domain\RepositoryInterface\PostRepositoryInterface;
use App\Post\Application\Dto\EditPostDto;
use App\Post\Application\UseCommand\EditPostUseCommand;
use App\Post\Domain\Entity\PostEntity;

class EditUseCase
{
    public function __construct(
        private readonly PostRepositoryInterface $repository
    ) {}

    public function handle(
        EditPostUseCommand $command
    ): EditPostDto {
        $entity = PostEntity::build(
            $command->toArray()
        );

        $this->repository->editById($entity);

        return EditPostDto::build(
            $entity
        );
    }
}