<?php

namespace App\Post\Application\ApplicationTest;

use App\Common\Domain\ValueObject\UserId;
use App\Post\Application\QueryServiceInterface\GetPostQueryServiceInterface;
use Tests\TestCase;
use Mockery;
use App\Post\Application\Dto\GetUserEachPostDto;
use App\Post\Application\Dto\GetAllUserPostDtoCollection;
use App\Common\Application\Dto\Pagination;
use App\Common\Domain\ValueObject\PostId;
use App\Post\Application\UseCase\GetAllUserPostUseCase;

class GetAllUserPostUseCaseTest extends TestCase
{
    private $userId;
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function arrayRequestData(): array
    {
        return [
            [
                'id' => 1,
                'userId' => $this->userId,
                'content' => 'Sample post content',
                'mediaPath' => 'path/to/media.jpg',
                'visibility' => 'public',
            ],
            [
                'id' => 2,
                'userId' => $this->userId,
                'content' => 'Another post content',
                'mediaPath' => 'path/to/another_media.jpg',
                'visibility' => 'private',
            ],
        ];
    }

    private function mockQueryService(): GetPostQueryServiceInterface
    {
        $mock = Mockery::mock(GetPostQueryServiceInterface::class);

        $mock->shouldReceive('getAllUserPosts')
            ->with(
                Mockery::on('is_int'),
                Mockery::on('is_int'),
                Mockery::on('is_int')
            )
            ->andReturn($this->mockPagination());

        return $mock;
    }

    private function mockPagination(): Pagination
    {
        $mock = Mockery::mock(Pagination::class);

        $mock->shouldReceive('getCurrentPage')
            ->andReturn(1);

        $mock->shouldReceive('getPerPage')
            ->andReturn(10);

        $mock->shouldReceive('getTotal')
            ->andReturn(2);

        $mock->shouldReceive('getItems')
            ->andReturn($this->mockDtoCollection()->getPosts());

        return $mock;
    }

    private function mockDtoCollection(): GetAllUserPostDtoCollection
    {
        $mock = Mockery::mock(GetAllUserPostDtoCollection::class);

        $mock->shouldReceive('getPosts')
            ->andReturn(array_map(function ($data) {
                return $this->mockDto();
            }, $this->arrayRequestData()));

        return $mock;
    }

    private function mockDto(): GetUserEachPostDto
    {
        $mock = Mockery::mock(GetUserEachPostDto::class);

        $mock->shouldReceive('getId')
            ->andReturn(new PostId($this->arrayRequestData()[0]['id']));

        $mock->shouldReceive('getUserId')
            ->andReturn(new UserId($this->arrayRequestData()[0]['userId']));

        $mock->shouldReceive('getContent')
            ->andReturn($this->arrayRequestData()[0]['content']);

        $mock->shouldReceive('getMediaPath')
            ->andReturn($this->arrayRequestData()[0]['mediaPath']);

        $mock->shouldReceive('getPostVisibility')
            ->andReturn($this->arrayRequestData()[0]['visibility']);

        return $mock;
    }

    public function test_use_case_check(): void
    {
        $queryService = $this->mockQueryService();

        $useCase = new GetAllUserPostUseCase($queryService);

        $result = $useCase->handle(
            $this->arrayRequestData()[0]['id'],
            1,
            10
        );

        $this->assertInstanceOf(Pagination::class, $result);
    }
}