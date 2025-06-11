<?php

namespace App\Common\Presentation\PresentationTest;

use Tests\TestCase;
use Mockery;
use App\Common\Presentation\ViewModel\Pagination;
use App\Common\Application\Dto\Pagination as PaginationDto;
use App\Common\Presentation\ViewModelFactory\PaginationFactory as PaginationViewModelFactory;
use App\Common\Application\DtoFactory\PaginationFactory as PaginationDtoFactory;

class PaginationViewModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function mockDto(): PaginationDto
    {
        $factory = Mockery::mock(
            'alias:' . PaginationDtoFactory::class
        );

        $dto = Mockery::mock(PaginationDto::class);

        $factory
            ->shouldReceive('build')
            ->andReturn($dto);

        $dto
            ->shouldReceive('getCurrentPage')
            ->andReturn(1);

        $dto
            ->shouldReceive('getPerPage')
            ->andReturn(10);

        $dto
            ->shouldReceive('getTotal')
            ->andReturn(100);

        $dto
            ->shouldReceive('getLastPage')
            ->andReturn(10);

        $dto
            ->shouldReceive('getFrom')
            ->andReturn(1);

        $dto
            ->shouldReceive('getTo')
            ->andReturn(10);

        $dto
            ->shouldReceive('getPath')
            ->andReturn('/api/items');

        $dto
            ->shouldReceive('getFirstPageUrl')
            ->andReturn('/api/items?page=1');

        $dto
            ->shouldReceive('getLastPageUrl')
            ->andReturn('/api/items?page=10');

        $dto
            ->shouldReceive('getNextPageUrl')
            ->andReturn('/api/items?page=2');

        $dto
            ->shouldReceive('getPrevPageUrl')
            ->andReturn(null);

        $dto
            ->shouldReceive('getLinks')
            ->andReturn([]);

        return $dto;
    }

    private function arrayData(): array
    {
        return [
            'data' => [
                'id' => 1,
                'name' => 'Cristiano Ronaldo',
            ],
            'current_page' => 1,
            'from' => 1,
            'to' => 10,
            'per_page' => 10,
            'path' => '/api/items',
            'last_page' => 10,
            'total' => 100,
            'first_page_url' => '/api/items?page=1',
            'last_page_url' => '/api/items?page=10',
            'next_page_url' => '/api/items?page=2',
            'prev_page_url' => null,
        ];
    }

    public function test_check_viewModel_type(): void
    {
        $dto = $this->mockDto();
        $viewModel = PaginationViewModelFactory::build($dto, $this->arrayData());

        $this->assertInstanceOf(Pagination::class, $viewModel);
    }

    public function test_check_viewModel_value(): void
    {
        $dto = $this->mockDto();
        $viewModel = PaginationViewModelFactory::build($dto, $this->arrayData());

        $this->assertInstanceOf(Pagination::class, $viewModel);

       foreach ($this->arrayData() as $key => $expectedValue) {
            $camelKey = lcfirst(str_replace('_', '', ucwords($key, '_')));
            $getter = 'get' . ucfirst($camelKey);

            if (method_exists($viewModel, $getter)) {
                $this->assertEquals($expectedValue, $viewModel->$getter());
            }
        }
    }
}