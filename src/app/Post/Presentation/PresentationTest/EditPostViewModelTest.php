<?php

namespace App\Post\Presentation\PresentationTest;

use Mockery;
use App\Post\Application\Dto\EditPostDto;
use App\Post\Presentation\ViewModel\EditPostViewModel;
use Tests\TestCase;
use App\Post\Domain\ValueObject\Postvisibility;
use App\Common\Domain\Enum\PostVisibility as EnumPostVisibility;
use App\Common\Domain\ValueObject\PostId;
use App\Common\Domain\ValueObject\UserId;

class EditPostViewModelTest extends TestCase
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
            'id' => 1,
            'userId' => 1,
            'content' => 'Updated content',
            'mediaPath' => 'https://example.com/updated_media.jpg',
            'visibility' => 'public',
        ];
    }

    private function mockDto(): EditPostDto
    {
        $dto = Mockery::mock(EditPostDto::class);

        $dto
            ->shouldReceive('getId')
            ->andReturn(new PostId($this->arrayData()['id']));

        $dto
            ->shouldReceive('getUserid')
            ->andReturn(new UserId($this->arrayData()['userId']));

        $dto
            ->shouldReceive('getContent')
            ->andReturn($this->arrayData()['content']);

        $dto
            ->shouldReceive('getMediaPath')
            ->andReturn($this->arrayData()['mediaPath']);

        $dto
            ->shouldReceive('getVisibility')
            ->andReturn(new Postvisibility(EnumPostVisibility::fromString($this->arrayData()['visibility'])));

        return $dto;
    }

    public function test_view_model_check_type(): void
    {
        $result = new EditPostViewModel($this->mockDto());

        $this->assertInstanceOf(EditPostViewModel::class, $result);
    }

    public function test_view_model_get_values(): void
    {
        $result = new EditPostViewModel($this->mockDto());

        $this->assertEquals($this->arrayData()['id'], $result->toArray()['id']);
        $this->assertEquals($this->arrayData()['userId'], $result->toArray()['userId']);
        $this->assertEquals($this->arrayData()['content'], $result->toArray()['content']);
        $this->assertEquals($this->arrayData()['mediaPath'], $result->toArray()['mediaPath']);
        $this->assertEquals(
            EnumPostVisibility::fromString($this->arrayData()['visibility'])->toLabel(),
            $result->toArray()['visibility']
        );
    }
}