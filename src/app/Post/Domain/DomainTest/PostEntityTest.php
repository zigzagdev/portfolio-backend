<?php

namespace App\Post\Domain\DomainTest;

use Illuminate\Support\Arr;
use Tests\TestCase;
use App\Post\Domain\Entity\PostEntity;
use App\Common\Domain\ValueObject\UserId;
use App\Post\Domain\ValueObject\Postvisibility;
use App\Common\Domain\ValueObject\PostId;
use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;

class PostEntityTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_post_entity_check_value(): void
    {
        $result = PostEntity::build($this->arrayData());

        $this->assertEquals($this->arrayData()['userId'], $result->getUserId()->getValue());
        $this->assertEquals($this->arrayData()['content'], $result->getContent());
        $this->assertEquals($this->arrayData()['mediaPath'], $result->getMediaPath());
        $this->assertEquals($this->arrayData()['visibility'], $result->getPostVisibility()->getStringValue());
    }

    public function test_post_entity_check_value_with_post_id(): void
    {
        $newArr = array_merge(['id' => 1], $this->arrayData());

        $result = PostEntity::build($newArr);

        $this->assertEquals($newArr['id'], $result->getId()->getValue());
        $this->assertEquals($newArr['userId'], $result->getUserId()->getValue());
        $this->assertEquals($newArr['content'], $result->getContent());
        $this->assertEquals($newArr['mediaPath'], $result->getMediaPath());
        $this->assertEquals($newArr['visibility'], $result->getPostVisibility()->getStringValue());
    }

    public function test_post_entity_check_type(): void
    {
        $result = PostEntity::build($this->arrayData());

        $this->assertInstanceOf(PostEntity::class, $result);
    }

    private function arrayData(): array
    {
        return [
            'userId' => 1,
            'content' => 'This is a test post.',
            'mediaPath' => 'https://example.com/media.jpg',
            'visibility' => 'public',
        ];
    }
}