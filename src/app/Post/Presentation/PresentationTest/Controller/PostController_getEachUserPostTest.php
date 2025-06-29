<?php

namespace App\Post\Presentation\PresentationTest\Controller;

use Illuminate\Http\JsonResponse;
use Tests\TestCase;
use App\Post\Presentation\ViewModel\GetAllUserPostViewModel;
use App\Post\Application\Dto\GetUserEachPostDto;
use Mockery;
use App\Post\Application\UseCase\GetUserEachPostUseCase;
use App\Post\Presentation\Controller\PostController;

class PostController_getEachUserPostTest extends TestCase
{
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

    private function mockUseCase(): GetUserEachPostUseCase
    {
        $useCase = Mockery::mock(GetUserEachPostUseCase::class);

        $useCase
            ->shouldReceive('handle')
            ->with(
                Mockery::type('int'),
                Mockery::type('int'),
            )
            ->andReturn($this->mockViewModel());

        return $useCase;
    }

    private function mockViewModel(): GetAllUserPostViewModel
    {
        $viewModel = Mockery::mock(GetAllUserPostViewModel::class);

        $viewModel
            ->shouldReceive('toArray')
            ->andReturn([]);

        return $viewModel;
    }

    private function mockDto(): GetUserEachPostDto
    {
        $dto = Mockery::mock(GetUserEachPostDto::class);

        $dto
            ->shouldReceive('toArray')
            ->andReturn($this->arrayData());

        return $dto;
    }

    private function arrayData(): array
    {
        return [
            'id' => 1,
            'userId' => $this->userId,
            'content' => 'Sample post content',
            'mediaPath' => 'https://example.com/media.jpg',
            'visibility' => 'public',
        ];
    }

    public function test_controller_method(): void
    {
        $response = $this->controller->getEachPost(
            $this->userId,
            $this->arrayData()['id'],
            $this->mockUseCase()
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}