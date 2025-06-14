<?php

namespace App\Post\Presentation\PresentationTest\Controller;

use App\Post\Application\UseCase\GetAllUserPostUseCase;
use Illuminate\Http\Request;
use Tests\TestCase;
use App\Post\Presentation\Controller\PostController;
use Mockery;
use App\Common\Application\Dto\Pagination as PaginationDto;
use App\Common\Presentation\ViewModelFactory\PaginationFactory;
use App\Post\Presentation\ViewModel\GetAllUserPostViewModel;
use App\Common\Presentation\ViewModel\Pagination as PaginationViewModel;
use Illuminate\Http\JsonResponse;

class PostController_getAllUserPostTest extends TestCase
{
    private int $userId;
    private int $perPage;
    private int $currentPage;
    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new PostController();
        $this->userId = 1;
        $this->perPage = 10;
        $this->currentPage = 1;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function mockUseCase(): GetAllUserPostUseCase
    {
        $mock = Mockery::mock(GetAllUserPostUseCase::class);

        $mock
            ->shouldReceive('handle')
            ->with(
                Mockery::type('int'),
                Mockery::type('int'),
                Mockery::type('int')
            )
            ->andReturn($this->mockPaginationDto());

        return $mock;
    }

    private function mockPaginationDto(): PaginationDto
    {
        $mock = Mockery::mock(PaginationDto::class);

        $mock
            ->shouldReceive('getCurrentPage')
            ->andReturn($this->currentPage);

        $mock
            ->shouldReceive('getPerPage')
            ->andReturn($this->perPage);

        return $mock;
    }

    private function mockViewModel(): GetAllUserPostViewModel
    {
        $mock = Mockery::mock(GetAllUserPostViewModel::class);

        $mock
            ->shouldReceive('build')
            ->with(Mockery::type(PaginationDto::class))
            ->andReturn($mock);

        $mock
            ->shouldReceive('toArray')
            ->andReturn([]);

        return $mock;
    }

    private function mockPaginationViewModel(): PaginationViewModel
    {
        $factory = Mockery::mock(
            'alias:' . PaginationFactory::class
        );

        $viewModel = Mockery::mock(
            PaginationViewModel::class
        );

        $factory
            ->shouldReceive('build')
            ->with(
                Mockery::type(PaginationDto::class),
                Mockery::type('array')
            )
            ->andReturn($viewModel);

        $viewModel
            ->shouldReceive('toArray')
            ->andReturn([
                'currentPage' => $this->currentPage,
                'perPage' => $this->perPage,
                'total' => 100,
                'lastPage' => 10,
                'from' => 1,
                'to' => 10,
                'path' => '/api/posts',
                'firstPageUrl' => '/api/posts?page=1',
                'lastPageUrl' => '/api/posts?page=10',
                'nextPageUrl' => '/api/posts?page=2',
                'prevPageUrl' => null,
                'links' => [],
            ]);

        return $viewModel;
    }

    private function mockRequest(): Request
    {
        $mock = Mockery::mock(Request::class);

        $mock
            ->shouldReceive('query')
            ->with('current_page', 1)
            ->andReturn($this->currentPage);

        $mock
            ->shouldReceive('query')
            ->with('per_page', 10)
            ->andReturn($this->perPage);

        return $mock;
    }

    public function test_check_response_type(): void
    {
        $response = $this->controller->getAllPosts(
            $this->userId,
            $this->mockRequest(),
            $this->mockUseCase(),
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}
