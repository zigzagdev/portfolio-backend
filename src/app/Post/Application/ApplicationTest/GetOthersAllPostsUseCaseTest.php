<?php

namespace App\Post\Application\ApplicationTest;

use Tests\TestCase;
use App\Post\Application\UseCase\GetOthersAllPostsUseCase;
use App\Post\Application\QueryServiceInterface\GetPostQueryServiceInterface;
use Mockery;
use App\Common\Application\Dto\Pagination as PaginationDto;

class GetOthersAllPostsUseCaseTest extends TestCase
{
    private $userId;
    private $perPage;
    private $currentPage;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userId = 1;
        $this->perPage = 10;
        $this->currentPage = 1;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function mockQueryService(): GetPostQueryServiceInterface
    {
        $queryService = Mockery::mock(GetPostQueryServiceInterface::class);

        $queryService
            ->shouldReceive('getOthersAllPosts')
            ->with(
                Mockery::on(fn($arg) => is_int($arg) && $arg > 0), // userId
                Mockery::on(fn($arg) => is_int($arg) && $arg > 0), // perPage
                Mockery::on(fn($arg) => is_int($arg) && $arg >= 0) // currentPage
            )
            ->andReturn($this->paginationDto());

        return $queryService;
    }

    private function paginationDto(): PaginationDto
    {
        $dto = Mockery::mock(PaginationDto::class);

        $dto
            ->shouldReceive('getPerPage')
            ->andReturn(10);

        $dto
            ->shouldReceive('getCurrentPage')
            ->andReturn(1);

        $dto
            ->shouldReceive('getTotal')
            ->andReturn(100);

        $dto
            ->shouldReceive('getFrom')
            ->andReturn(1);

        $dto
            ->shouldReceive('getTo')
            ->andReturn(10);

        $dto
            ->shouldReceive('getPath')
            ->andReturn('/posts/others');

        return $dto;
    }

    public function test_use_case_work_correctly(): void
    {
        $useCase = new GetOthersAllPostsUseCase(
            $this->mockQueryService()
        );

        $result = $useCase->handle(
            userId: $this->userId,
            perPage: $this->perPage,
            currentPage: $this->currentPage
        );

        $this->assertInstanceOf(PaginationDto::class, $result);
    }
}