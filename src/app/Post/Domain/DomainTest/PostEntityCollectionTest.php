<?php

namespace App\Post\Domain\DomainTest;

use Tests\TestCase;
use App\Post\Domain\Entity\PostEntity;
use App\Post\Domain\ValueObject\PostVisibility;
use App\Common\Domain\ValueObject\PostId;
use App\Common\Domain\ValueObject\UserId;
use App\Post\Domain\Entity\PostEntityCollection;
use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;

class PostEntityCollectionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @return array
     */
    public function arrayMultiData(): array
    {
        return [
            0 => [
                'id' => 1,
                'userId' => 1,
                'content' => 'First post content',
                'mediaPath' => null,
                'visibility' => 'public',
            ],
            1 => [
                'id' => 2,
                'userId' => 1,
                'content' => 'Second post content',
                'mediaPath' => 'path/to/media.jpg',
                'visibility' => 'private',
            ],
        ];
    }

    public function test_check_collection_type(): void
    {
        $posts = PostEntityCollection::build($this->arrayMultiData());

        $this->assertInstanceOf(PostEntityCollection::class, $posts);
    }

    public function test_check_collection_value(): void
    {
        $posts = PostEntityCollection::build($this->arrayMultiData());

        $this->assertCount(2, $posts->getPosts());

        foreach ($posts->getPosts() as $index => $post) {
            $this->assertInstanceOf(PostEntity::class, $post);
            $this->assertEquals(new PostId($this->arrayMultiData()[$index]['id']), $post->getId());
            $this->assertEquals(new UserId($this->arrayMultiData()[$index]['userId']), $post->getUserId());
            $this->assertEquals($this->arrayMultiData()[$index]['content'], $post->getContent());
            $this->assertEquals($this->arrayMultiData()[$index]['mediaPath'], $post->getMediaPath());
            $this->assertEquals(
                new PostVisibility(PostVisibilityEnum::fromString($this->arrayMultiData()[$index]['visibility'])),
                $post->getPostVisibility()
            );
        }
    }
}