<?php

namespace App\Post\Presentation\PresentationTest\Controller;

use App\Post\Application\UseCase\GetOthersAllPostsUseCase;
use App\Post\Presentation\Controller\PostController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tests\TestCase;
use Mockery;
use App\Common\Application\Dto\Pagination as PaginationDto;

class PostController_getOthersPostsTest extends TestCase
{

    private $currentPage;
    private $perPage;

    private $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->currentPage = 1;
        $this->perPage = 10;
        $this->controller = new PostController();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function mockUseCase(): GetOthersAllPostsUseCase
    {
        $useCase = Mockery::mock(GetOthersAllPostsUseCase::class);

        $useCase->shouldReceive('handle')
            ->with(
                Mockery::type('int'),
                Mockery::type($this->perPage),
                Mockery::type($this->currentPage)
            )
            ->andReturn($this->mockPagination());

        return $useCase;
    }

    private function mockPagination(): PaginationDto
    {
        $paginationDto = Mockery::mock(PaginationDto::class);

        $paginationDto->shouldReceive('getCurrentPage')
            ->andReturn($this->currentPage);

        $paginationDto->shouldReceive('getPerPage')
            ->andReturn($this->perPage);

        return $paginationDto;
    }

    private function mockRequest(): Request
    {
        $request = Mockery::mock(Request::class);

        $request->shouldReceive('input')
            ->with('perPage')
            ->andReturn($this->perPage);

        $request->shouldReceive('input')
            ->with('currentPage')
            ->andReturn($this->currentPage);

        return $request;
    }

    public function test_controller_type_check(): void
    {
        $result = $this->controller->getOthersPosts(
            $this->mockRequest(),
            1,
            $this->mockUseCase()
        );

        $this->assertInstanceOf(JsonResponse::class, $result);
    }
}