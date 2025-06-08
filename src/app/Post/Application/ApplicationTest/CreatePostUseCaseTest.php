<?php

namespace App\Post\Application\ApplicationTest;

use Tests\TestCase;
use App\Common\Domain\ValueObject\PostId;
use App\Post\Application\Dto\CreatePostDto;
use App\Post\Application\UseCase\CreateUseCase;
use App\Post\Application\UseCommand\CreatePostUseCommand;
use App\Post\Domain\Entity\PostEntity;
use App\Post\Domain\ValueObject\PostVisibility;
use App\Post\Domain\RepositoryInterface\PostRepositoryInterface;
use Mockery;
use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;
use App\Common\Domain\ValueObject\UserId;

class CreatePostUseCaseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function mockEntity(): PostEntity
    {
        $entity = Mockery::mock(PostEntity::class);
        $entity
            ->shouldReceive('getId')
            ->andReturn(new PostId(1));
        $entity
            ->shouldReceive('getUserId')
            ->andReturn(new UserId($this->arrayData()['userId']));
        $entity
            ->shouldReceive('getContent')
            ->andReturn($this->arrayData()['content']);
        $entity
            ->shouldReceive('getMediaPath')
            ->andReturn($this->arrayData()['mediaPath']);
        $entity
            ->shouldReceive('getPostVisibility')
            ->andReturn(new PostVisibility(PostVisibilityEnum::PUBLIC));

        return $entity;
    }

    private function arrayData(): array
    {
        return [
            'userId' => 1,
            'content' => 'Cristiano Ronaldo is the best player in the world',
            'mediaPath' => null,
            'visibility' => 'public',
        ];
    }

    private function mockCommand(): CreatePostUseCommand
    {
        $command = Mockery::mock(CreatePostUseCommand::class);

        $command
            ->shouldReceive('toArray')
            ->andReturn($this->arrayData());

        return $command;
    }

    private function mockRepository(): PostRepositoryInterface
    {
        $repository = Mockery::mock(PostRepositoryInterface::class);

        $repository
            ->shouldReceive('save')
            ->with(Mockery::type(PostEntity::class))
            ->andReturn($this->mockEntity());

        return $repository;
    }

    private function mockDto(): CreatePostDto
    {
        $dto = Mockery::mock(CreatePostDto::class);

        $dto
            ->shouldReceive('getId')
            ->andReturn(new PostId(1));

        $dto
            ->shouldReceive('getUserid')
            ->andReturn(new UserId($this->arrayData()['userId']));

        $dto
            ->shouldReceive('getContent')
            ->andReturn($this->arrayData()['content']);

        $dto
            ->shouldReceive('getMediaPath')
            ->andReturn($this->arrayData()['mediaPath']);

        $dto
            ->shouldReceive('getVisibility')
            ->andReturn(
                new PostVisibility(PostVisibilityEnum::from($this->arrayData()['visibility']))
            );

        $dto
            ->shouldReceive('toArray')
            ->andReturn($this->arrayData());

        return $dto;
    }

    public function test1(): void
    {
        $useCase = new CreateUseCase(
            $this->mockRepository()
        );

        $result = $useCase->handle(
            $this->mockCommand()
        );

        $this->assertInstanceOf(CreatePostDto::class, $result);
    }
}