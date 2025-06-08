<?php

namespace App\Post\Presentation\PresentationTest\Controller;

use App\Common\Domain\ValueObject\PostId;
use App\Common\Domain\ValueObject\UserId;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tests\TestCase;
use Mockery;
use App\Post\Application\UseCase\CreateUseCase;
use App\Post\Application\Dto\CreatePostDto;
use App\Post\Presentation\ViewModel\CreatePostViewModel;
use App\Post\Application\UseCommand\CreatePostUseCommand;
use App\Post\Presentation\Controller\PostController;

class PostController_createTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new PostController();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function arrayData(): array
    {
        return [
            'user_id' => 1,
            'content' => 'Nuno Mendez',
            'media_path' => 'https://example.com/media.jpg',
            'visibility' => 'public',
        ];
    }

    private function mockUseCommand(): CreatePostUseCommand
    {
        $command = Mockery::mock(CreatePostUseCommand::class);

        $command
            ->shouldReceive('toArray')
            ->andReturn($this->arrayData());

        return $command;
    }

    private function mockUseCase(): CreateUseCase
    {
        $useCase = Mockery::mock(CreateUseCase::class);

        $useCase
            ->shouldReceive('handle')
            ->with($this->mockUseCommand())
            ->andReturn($this->mockDto());

        return $useCase;
    }

    private function mockDto(): CreatePostDto
    {
        $dto = Mockery::mock(CreatePostDto::class);

        $dto
            ->shouldReceive('getId')
            ->andReturn(new PostId(1));

        $dto
            ->shouldReceive('getUserid')
            ->andReturn(new UserId($this->arrayData()['user_id']));

        $dto
            ->shouldReceive('getContent')
            ->andReturn($this->arrayData()['content']);

        $dto
            ->shouldReceive('getMediaPath')
            ->andReturn($this->arrayData()['media_path']);

        $dto
            ->shouldReceive('getVisibility')
            ->andReturn($this->arrayData()['visibility']);

        return $dto;
    }

    private function mockViewModel(): CreatePostViewModel
    {
        $dto = $this->mockDto();

        $viewModel = Mockery::mock(CreatePostViewModel::class);

        $viewModel
            ->shouldReceive('toArray')
            ->andReturn($dto->toArray());

        return $viewModel;
    }

    private function mockRequest(): Request
    {
        $request = Mockery::mock(Request::class);

        $request
            ->shouldReceive('all')
            ->andReturn($this->arrayData());

        return $request;
    }

    public function test_controller_check_type(): void
    {
        $useCase = $this->mockUseCase();
        $request = $this->mockRequest();

        $response = $this->controller->create(
            $request,
            $this->arrayData()['user_id'],
            $useCase
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}
