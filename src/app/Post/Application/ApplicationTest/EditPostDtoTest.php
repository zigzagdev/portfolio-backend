<?php

namespace App\Post\Application\ApplicationTest;

use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;
use App\Common\Domain\ValueObject\UserId;
use App\Post\Domain\ValueObject\Postvisibility;
use Tests\TestCase;
use App\Post\Application\Dto\EditPostDto;
use App\Post\Domain\Entity\PostEntity;
use Mockery;
use App\Common\Domain\ValueObject\PostId;

class EditPostDtoTest extends TestCase
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

    private function mockEntity(): PostEntity
    {
        $entity = Mockery::mock(PostEntity::class);

        $entity
            ->shouldReceive('getId')
            ->andReturn(new PostId($this->arrayData()['id']));

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

    public function test_dto_check_type(): void
    {
        $result = new EditPostDto($this->mockEntity());

        $this->assertInstanceOf(EditPostDto::class, $result);
    }

    public function test_dto_check_value(): void
    {
        $dto = new EditPostDto($this->mockEntity());

        $this->assertEquals(1, $dto->getId()->getValue());
        $this->assertEquals(1, $dto->getUserid()->getValue());
        $this->assertEquals('Updated content', $dto->getContent());
        $this->assertEquals('https://example.com/updated_media.jpg', $dto->getMediaPath());
        $this->assertEquals(
            new Postvisibility(PostVisibilityEnum::fromString($this->arrayData()['visibility'])),
            $dto->getVisibility()
        );
    }
}