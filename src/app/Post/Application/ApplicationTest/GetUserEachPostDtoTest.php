<?php

namespace App\Post\Application\ApplicationTest;

use Tests\TestCase;
use App\Post\Application\Dto\GetUserEachPostDto;

class GetUserEachPostDtoTest extends TestCase
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
            'id' => 1,
            'userId' => 2,
            'content' => 'Sample post content',
            'mediaPath' => 'path/to/media.jpg',
            'visibility' => 'public',
        ];
    }

    public function test_get_all_user_post_dto() : void
    {
        $data = $this->arrayRequestData();
        $dto = GetUserEachPostDto::build($data);

        $this->assertInstanceOf(GetUserEachPostDto::class, $dto);
    }

    public function test_get_all_user_post_dto_values() : void
    {
        $data = $this->arrayRequestData();
        $dto = GetUserEachPostDto::build($data);

        $this->assertEquals($dto->id, $data['id']);
        $this->assertEquals($dto->userId, $data['userId']);
        $this->assertEquals($dto->content, $data['content']);
        $this->assertEquals($dto->mediaPath, $data['mediaPath']);
        $this->assertEquals($dto->visibility, $data['visibility']);
    }
}