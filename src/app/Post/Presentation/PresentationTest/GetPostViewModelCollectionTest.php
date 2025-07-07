<?php

namespace App\Post\Presentation\PresentationTest;

use App\Post\Domain\Entity\PostEntityCollection;
use Tests\TestCase;
use App\Post\Presentation\ViewModel\GetPostViewModel;
use App\Post\Application\Dto\GetAllUserPostDtoCollection;
use Mockery;
use App\Post\Presentation\ViewModel\GetPostsViewModelCollection;
use App\Common\Application\Dto\Pagination as PaginationDto;
use App\Post\Application\Dto\GetUserEachPostDto;

class GetPostViewModelCollectionTest extends TestCase
{
    private $currentPage;
    private $perPage;
    protected function setUp(): void
    {
        parent::setUp();
        $this->currentPage = 1;
        $this->perPage = 10;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function mockPagination(): PaginationDto
    {
        $pagination = Mockery::mock(PaginationDto::class);

        $pagination->shouldReceive('getCurrentPage')
            ->andReturn($this->currentPage);

        $pagination->shouldReceive('getPerPage')
            ->andReturn($this->perPage);

        return $pagination;
    }

    private function mockDtoCollection(): GetAllUserPostDtoCollection
    {
        $dto1 = new GetUserEachPostDto(
            id: 1,
            userId: 1,
            content: 'Sample content',
            mediaPath: 'https://example.com/media.jpg',
            visibility: 'public'
        );

        $dto2 = new GetUserEachPostDto(
            id: 2,
            userId: 1,
            content: 'Another content',
            mediaPath: 'https://example.com/another_media.jpg',
            visibility: 'private'
        );

        $collection = Mockery::mock(GetAllUserPostDtoCollection::class);
        $collection->shouldReceive('getPosts')->andReturn([$dto1, $dto2]);

        return $collection;
    }

    private function arrayData(): array
    {
        return [
            [
                'id' => 1,
                'userId' => 1,
                'content' => 'Sample content',
                'mediaPath' => 'https://example.com/media.jpg',
                'visibility' => 'public'
            ],
            [
                'id' => 2,
                'userId' => 1,
                'content' => 'Another content',
                'mediaPath' => 'https://example.com/another_media.jpg',
                'visibility' => 'private'
            ]
        ];
    }

    public function test_view_model_collection_check_type(): void
    {
        $collection = new GetPostsViewModelCollection(
            $this->mockDtoCollection()
        );

        $this->assertInstanceOf(
            GetPostsViewModelCollection::class,
            $collection
        );
    }

    public function test_view_model_collection_check_value(): void
    {
        $collection = new GetPostsViewModelCollection(
            $this->mockDtoCollection()
        );

        $this->assertEquals(
            $this->arrayData(),
            $collection->toArray()
        );
    }
}