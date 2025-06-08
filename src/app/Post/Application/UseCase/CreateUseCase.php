<?php

namespace App\Post\Application\UseCase;

use App\Post\Application\Dto\CreatePostDto;
use App\Post\Application\UseCommand\CreatePostUseCommand;
use App\Post\Domain\Entity\PostEntity;
use App\Post\Domain\EntityFactory\PostFromModelEntityFactory;
use App\Post\Domain\RepositoryInterface\PostRepositoryInterface;

class CreateUseCase
{
    public function __construct(
        private readonly PostRepositoryInterface $repository
    ) {}

    public function handle(
        CreatePostUseCommand $command
    ) {
        $entity = PostEntity::build(
            $command->toArray()
        );

        return CreatePostDto::build(
            $this->repository->save($entity)
        );
    }
}