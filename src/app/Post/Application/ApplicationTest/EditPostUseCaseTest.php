<?php

namespace App\Post\Application\ApplicationTest;

use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;
use App\Common\Domain\ValueObject\PostId;
use App\Common\Domain\ValueObject\UserId;
use App\Post\Domain\Entity\PostEntity;
use App\Post\Domain\ValueObject\Postvisibility;
use Tests\TestCase;
use App\Post\Application\UseCase\EditUseCase;
use Mockery;
use App\Post\Application\UseCommand\EditPostUseCommand;
use App\Post\Domain\RepositoryInterface\PostRepositoryInterface;
use App\Post\Application\Dto\EditPostDto;

class EditPostUseCaseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function arrayData(): array
    {
        return [
            'id' => 1,
            'userId' => 1,
            'content' => 'Updated content',
            'mediaPath' => 'https://example.com/updated_media.jpg',
            'visibility' => 'public',
        ];
    }

    private function mockEntity(): PostEntity
    {
        $entity = Mockery::mock(PostEntity::class);

        $entity
            ->shouldReceive('getId')
            ->andReturn(new PostId($this->arrayData()['id']));

        $entity
            ->shouldReceive('getUserId')
            ->andReturn(new UserId(1));

        $entity
            ->shouldReceive('getContent')
            ->andReturn($this->arrayData()['content']);

        $entity
            ->shouldReceive('getMediaPath')
            ->andReturn($this->arrayData()['mediaPath']);

        $entity
            ->shouldReceive('getPostVisibility')
            ->andReturn(new Postvisibility(PostVisibilityEnum::fromString($this->arrayData()['visibility'])));

        return $entity;
    }

    private function mockUseCommand(): EditPostUseCommand
    {
        $command = Mockery::mock(EditPostUseCommand::class);

        $command
            ->shouldReceive('getId')
            ->andReturn($this->arrayData()['id']);

        $command
            ->shouldReceive('getUserId')
            ->andReturn($this->arrayData()['userId']);

        $command
            ->shouldReceive('getContent')
            ->andReturn($this->arrayData()['content']);

        $command
            ->shouldReceive('getMediaPath')
            ->andReturn($this->arrayData()['mediaPath']);

        $command
            ->shouldReceive('getVisibility')
            ->andReturn(new Postvisibility(PostVisibilityEnum::fromString($this->arrayData()['visibility'])));

        $command
            ->shouldReceive('toArray')
            ->andReturn($this->arrayData());

        return $command;
    }

    private function mockDto(): EditPostDto
    {
        $dto = Mockery::mock(EditPostDto::class);

        $dto
            ->shouldReceive('getId')
            ->andReturn(new PostId($this->arrayData()['id']));

        $dto
            ->shouldReceive('getUserId')
            ->andReturn(new UserId(1));

        $dto
            ->shouldReceive('getContent')
            ->andReturn($this->arrayData()['content']);

        $dto
            ->shouldReceive('getMediaPath')
            ->andReturn($this->arrayData()['mediaPath']);

        $dto
            ->shouldReceive('getVisibility')
            ->andReturn(new Postvisibility(PostVisibilityEnum::fromString($this->arrayData()['visibility'])));

        return $dto;
    }

    private function mockRepository(): PostRepositoryInterface
    {
        $repository = Mockery::mock(PostRepositoryInterface::class);

        $repository
            ->shouldReceive('editById')
            ->with(Mockery::type(PostEntity::class))
            ->andReturn($this->mockEntity());

        return $repository;
    }

    public function test_use_case(): void
    {
        $useCase = new EditUseCase(
            $this->mockRepository()
        );

        $result = $useCase->handle($this->mockUseCommand());

        $this->assertInstanceOf(EditPostDto::class, $result);
    }
}