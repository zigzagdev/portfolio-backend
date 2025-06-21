<?php

namespace App\Post\Presentation\PresentationTest\Controller;

use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;
use App\Common\Domain\ValueObject\PostId;
use App\Common\Domain\ValueObject\UserId;
use App\Post\Application\Dto\EditPostDto;
use App\Post\Application\UseCase\EditUseCase;
use App\Post\Application\UseCommand\EditPostUseCommand;
use App\Post\Domain\ValueObject\Postvisibility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tests\TestCase;
use App\Post\Presentation\Controller\PostController;
use Mockery;

class PostController_editTest extends TestCase
{
    private $controller;
    private $userId;
    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new PostController();
        $this->userId = 1;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
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

    private function mockUseCase(): EditUseCase
    {
        $useCase = Mockery::mock(EditUseCase::class);

        $useCase
            ->shouldReceive('handle')
            ->with(Mockery::type(EditPostUseCommand::class))
            ->andReturn($this->mockDto());

        return $useCase;
    }

    private function arrayData(): array
    {
        return [
            'id' => 1,
            'userId' => $this->userId,
            'content' => 'Updated content',
            'mediaPath' => 'https://example.com/updated_media.jpg',
            'visibility' => 'public',
        ];
    }

    private function mockRequest(): Request
    {
        $request = Mockery::mock(Request::class);

        $request
            ->shouldReceive('all')
            ->andReturn($this->arrayData());

        return $request;
    }

    public function test_controller(): void
    {
        $result = $this->controller->edit(
            $this->mockRequest(),
            $this->userId,
            $this->arrayData()['id'],
            $this->mockUseCase()
        );

        $this->assertInstanceOf(JsonResponse::class, $result);
    }
}