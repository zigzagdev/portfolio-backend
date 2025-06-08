<?php

namespace App\Post\Application\ApplicationTest;

use App\Common\Domain\ValueObject\UserId;
use App\Post\Domain\ValueObject\Postvisibility;
use Tests\TestCase;
use Mockery;
use App\Post\Domain\Entity\PostEntity;
use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;
use App\Post\Application\Dto\CreatePostDto;

class CreatePostDtoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function mockEntity(): PostEntity
    {
        $entity = Mockery::mock(PostEntity::class);

        $entity
            ->shouldReceive('getUserId')
            ->andReturn(new UserId(1));

        $entity
            ->shouldReceive('getContent')
            ->andReturn($this->arrayData()['content']);

        $entity
            ->shouldReceive('getMediaPath')
            ->andReturn($this->arrayData()['mediaPath']);

        $entity
            ->shouldReceive('getPostVisibility')
            ->andReturn(new Postvisibility(PostVisibilityEnum::fromString($this->arrayData()['visibility'])));

        return $entity;
    }

    private function arrayData(): array
    {
        return [
            'userId' => 1,
            'content' => 'This is a test post content.',
            'mediaPath' => null,
            'visibility' => 'public',
        ];
    }

    public function test_dto_check_type(): void
    {
        $result = new CreatePostDto($this->mockEntity());

        $this->assertInstanceOf(CreatePostDto::class, $result);
    }

    public function test_dto_check_value(): void
    {
        $result = new CreatePostDto($this->mockEntity());

        $this->assertEquals($this->arrayData()['userId'], $result->getUserid()->getValue());
        $this->assertEquals($this->arrayData()['content'], $result->getContent());
        $this->assertEquals($this->arrayData()['mediaPath'], $result->getMediaPath());
        $this->assertEquals(
            new Postvisibility(PostVisibilityEnum::fromString($this->arrayData()['visibility'])),
            $result->getVisibility()
        );
    }
}