<?php

namespace App\Post\Presentation\PresentationTest;

use App\Post\Application\Dto\GetUserEachPostDto;
use Tests\TestCase;
use App\Post\Presentation\ViewModel\GetPostViewModel;
use App\Common\Application\Dto\Pagination as PaginationDto;
use Mockery;

class GetPostViewModelTest extends TestCase
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
        $dto = Mockery::mock(PaginationDto::class);

        $dto->shouldReceive('getCurrentPage')
            ->andReturn($this->currentPage);

        $dto->shouldReceive('getPerPage')
            ->andReturn($this->perPage);

        $dto->shouldReceive('getData')
            ->andReturn($this->arrayData());

        return $dto;
    }

    private function mockDto(): GetUserEachPostDto
    {
        $dto = Mockery::mock(GetUserEachPostDto::class);

        $dto->shouldReceive('getId')
            ->andReturn(1);

        $dto->shouldReceive('getUserId')
            ->andReturn(1);

        $dto->shouldReceive('getContent')
            ->andReturn('Sample content');

        $dto->shouldReceive('getMediaPath')
            ->andReturn('https://example.com/media.jpg');

        $dto->shouldReceive('getVisibility')
            ->andReturn(0);

        return $dto;
    }

    private function arrayData(): array
    {
        return [
                'id' => 1,
                'userId' => 1,
                'content' => 'Sample content',
                'mediaPath' => 'https://example.com/media.jpg',
                'visibility' => 0,
        ];
    }

    public function test_view_model_check_type(): void
    {
        $viewModel = new GetPostViewModel(
            $this->arrayData()['id'],
            $this->arrayData()['userId'],
            $this->arrayData()['content'],
            $this->arrayData()['mediaPath'],
            $this->arrayData()['visibility']
        );

        $this->assertInstanceOf(GetPostViewModel::class, $viewModel);
    }

    public function test_view_model_check_value(): void
    {
        $viewModel = new GetPostViewModel(
            $this->arrayData()['id'],
            $this->arrayData()['userId'],
            $this->arrayData()['content'],
            $this->arrayData()['mediaPath'],
            $this->arrayData()['visibility']
        );

        $this->assertEquals($viewModel->toArray()['id'], $this->mockPagination()->getData()['id']);
        $this->assertEquals($viewModel->toArray()['userId'], $this->mockPagination()->getData()['userId']);
        $this->assertEquals($viewModel->toArray()['content'], $this->mockPagination()->getData()['content']);
        $this->assertEquals($viewModel->toArray()['mediaPath'], $this->mockPagination()->getData()['mediaPath']);
        $this->assertEquals(
            $viewModel->toArray()['visibility'],
            $this->mockPagination()->getData()['visibility']
        );
    }
}