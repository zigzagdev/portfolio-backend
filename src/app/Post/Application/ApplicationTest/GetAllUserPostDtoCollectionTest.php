<?php

namespace App\Post\Application\ApplicationTest;

use Tests\TestCase;
use App\Post\Application\Dto\GetAllUserPostDtoCollection;

class GetAllUserPostDtoCollectionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function arrayRequestData(): array
    {
        return [
            [
                'id' => 1,
                'userId' => 2,
                'content' => 'Sample post content',
                'mediaPath' => 'path/to/media.jpg',
                'visibility' => 'public',
            ],
            [
                'id' => 2,
                'userId' => 3,
                'content' => 'Another post content',
                'mediaPath' => 'path/to/another_media.jpg',
                'visibility' => 'private',
            ],
        ];
    }

    public function test_get_all_user_post_dto_collection(): void
    {
        $data = $this->arrayRequestData();
        $dtoCollection = GetAllUserPostDtoCollection::build($data);

        $this->assertInstanceOf(GetAllUserPostDtoCollection::class, $dtoCollection);
    }

    public function test_get_all_user_post_dto_collection_values(): void
    {
        $data = $this->arrayRequestData();
        $dtoCollection = GetAllUserPostDtoCollection::build($data);

        $this->assertCount(count($data), $dtoCollection->getPosts());

        foreach ($data as $index => $item) {
            $dto = $dtoCollection->getPosts()[$index];
            $this->assertEquals($dto->id, $item['id']);
            $this->assertEquals($dto->userId, $item['userId']);
            $this->assertEquals($dto->content, $item['content']);
            $this->assertEquals($dto->mediaPath, $item['mediaPath']);
            $this->assertEquals($dto->visibility, $item['visibility']);
        }
    }
}