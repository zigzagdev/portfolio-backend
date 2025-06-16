<?php

namespace App\Post\Domain\DomainTest;

use App\Common\Domain\ValueObject\UserId;
use App\Common\Domain\ValueObject\PostId;
use App\Post\Domain\Entity\PostEntity;
use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;
use App\Post\Domain\EntityFactory\EditPostEntityFactory;
use App\Post\Domain\ValueObject\Postvisibility;
use Tests\TestCase;
use InvalidArgumentException;

class EditPostEntityFactoryTest extends TestCase
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
            'content' => 'Bruno to Ronaldo siuuuuu',
            'mediaPath' => 'https://example.com/media.jpg',
            'visibility' => 'public',
        ];
    }

    private function wrongIdArrayData(): array
    {
        return [
            'id' => "1",
            'userId' => 1,
            'content' => '',
            'mediaPath' => 'https://example.com/media.jpg',
            'visibility' => 'public',
        ];
    }

    private function wrongUserIdArrayData(): array
    {
        return [
            'id' => 1,
            'userId' => "1",
            'content' => '',
            'mediaPath' => 'https://example.com/media.jpg',
            'visibility' => 'public',
        ];
    }

    public function test_check_entity_type(): void
    {
        $postEntity = EditPostEntityFactory::build($this->arrayData());

        $this->assertInstanceOf(PostEntity::class, $postEntity);
    }

    public function test_check_entity_value(): void
    {
        $postEntity = EditPostEntityFactory::build($this->arrayData());

        $this->assertEquals(new PostId($this->arrayData()['id']), $postEntity->getId());
        $this->assertEquals(new UserId($this->arrayData()['userId']), $postEntity->getUserId());
        $this->assertEquals($this->arrayData()['content'], $postEntity->getContent());
        $this->assertEquals($this->arrayData()['mediaPath'], $postEntity->getMediaPath());
        $this->assertEquals(
            new Postvisibility(PostVisibilityEnum::fromString($this->arrayData()['visibility'])),
            $postEntity->getPostVisibility()
        );
    }

    public function test_check_wrong_user_id_type(): void
    {
        $this->expectException(InvalidArgumentException::class);
        EditPostEntityFactory::build($this->wrongUserIdArrayData());
    }

    public function test_check_wrong_id_type(): void
    {
        $this->expectException(InvalidArgumentException::class);
        EditPostEntityFactory::build($this->wrongIdArrayData());
    }
}