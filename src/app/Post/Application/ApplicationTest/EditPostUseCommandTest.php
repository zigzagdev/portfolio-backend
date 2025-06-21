<?php

namespace App\Post\Application\ApplicationTest;

use Tests\TestCase;
use InvalidArgumentException;
use App\Post\Application\UseCommand\EditPostUseCommand;

class EditPostUseCommandTest extends TestCase
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

    private function requestData(): array
    {
        return [
            'id' => 1,
            'userId' => $this->userId,
            'content' => 'Updated content',
            'mediaPath' => 'https://example.com/updated_media.jpg',
            'visibility' => 'public',
        ];
    }

    public function test_check_use_command_type(): void
    {
        $useCommand = EditPostUseCommand::build($this->requestData());

        $this->assertInstanceOf(EditPostUseCommand::class, $useCommand);
    }

    public function test_check_use_command_value(): void
    {
        $useCommand = EditPostUseCommand::build($this->requestData());

        $this->assertEquals(1, $useCommand->toArray()['id']);
        $this->assertEquals($this->userId, $useCommand->toArray()['userId']);
        $this->assertEquals('Updated content', $useCommand->toArray()['content']);
        $this->assertEquals('https://example.com/updated_media.jpg', $useCommand->toArray()['mediaPath']);
        $this->assertEquals('public', $useCommand->toArray()['visibility']);
    }

    public function test_invalid_use_command_request(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $invalidData = [
            'id' => 1,
            'userId' => $this->userId,
            // 'content' is missing
            'mediaPath' => 'https://example.com/updated_media.jpg',
            'visibility' => 'public',
        ];

        EditPostUseCommand::build($invalidData);
    }
}