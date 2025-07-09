<?php

namespace App\Post\Presentation\PresentationTest;

use App\Post\Presentation\ViewModel\GetAllUserPostViewModel;
use Tests\TestCase;
use App\Post\Application\Dto\GetUserEachPostDto;
use Mockery;

class GetAllUserPostViewModelTest extends TestCase
{
    private int $userId;
    protected function setUp(): void
    {
        parent::setUp();
        $this->userId = 1;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function arrayMultiData(): array
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

    private function mockDto(): GetUserEachPostDto
    {
        $mock = Mockery::mock(GetUserEachPostDto::class);

        $mock
            ->shouldReceive('toArray')
            ->andReturn($this->arrayMultiData()[0]);

        return $mock;
    }

    public function test_view_model_check_type(): void
    {
        $result = GetAllUserPostViewModel::build(
            $this->mockDto()
        );

        $this->assertInstanceOf(GetAllUserPostViewModel::class, $result);
    }

    public function test_view_model_check_value(): void
    {
        $result = GetAllUserPostViewModel::build(
            $this->mockDto()
        );

        $this->assertSame($this->arrayMultiData()[0]['id'], $result->toArray()['id']);
        $this->assertSame($this->arrayMultiData()[0]['userId'], $result->toArray()['userId']);
        $this->assertSame($this->arrayMultiData()[0]['content'], $result->toArray()['content']);
        $this->assertSame($this->arrayMultiData()[0]['mediaPath'], $result->toArray()['mediaPath']);
        $this->assertSame($this->arrayMultiData()[0]['visibility'], $result->toArray()['visibility']);
    }
}