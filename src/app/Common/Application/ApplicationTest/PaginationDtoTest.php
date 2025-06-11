<?php

namespace App\Common\Application\ApplicationTest;

use Tests\TestCase;
use App\Common\Application\Dto\Pagination;
use App\Common\Application\DtoFactory\PaginationFactory;

class PaginationDtoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function arrayData(): array
    {
        return [
            'data' => [
                'id' =>1,
                'title' => 'Portugal was the winner of the Nations League 2025',
            ],
            'current_page' => 2,
            'from' => 11,
            'to' => 20,
            'per_page' => 10,
            'path' => '/posts',
            'last_page' => 5,
            'total' => 50,
            'first_page_url' => '/posts?page=1',
            'last_page_url' => '/posts?page=5',
            'next_page_url' => '/posts?page=3',
            'prev_page_url' => '/posts?page=1',
            'links' => [],
        ];
    }


    public function test_check_pagination_type(): void
    {
        $result = PaginationFactory::build($this->arrayData()['data'], $this->arrayData());

        $this->assertInstanceOf(Pagination::class, $result);
    }

    public function test_check_pagination_values(): void
    {
        $result = PaginationFactory::build($this->arrayData()['data'], $this->arrayData());

        foreach ($this->arrayData() as $key => $expectedValue) {
            if ($key === 'data') {
                continue;
            }
            $getter = 'get' . ucfirst(str_replace('_', '', ucwords($key, '_')));
            $this->assertEquals($expectedValue, $result->$getter());
        }
    }
}