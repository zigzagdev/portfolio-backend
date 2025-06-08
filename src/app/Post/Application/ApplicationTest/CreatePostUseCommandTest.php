<?php

namespace App\Post\Application\ApplicationTest;

use Tests\TestCase;
use Mockery;
use App\Post\Application\UseCommand\CreatePostUseCommand;
use App\Post\Domain\Entity\PostEntity;

class CreatePostUseCommandTest extends TestCase
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
            ->andReturn(1);

        $entity
            ->shouldReceive('getContent')
            ->andReturn('This is a test post content.');

        $entity
            ->shouldReceive('getMediaPath')
            ->andReturn(null);

        $entity
            ->shouldReceive('getVisibility')
            ->andReturn('public');

        return $entity;
    }

    public function test1_check_type(): void
    {
        $result = CreatePostUseCommand::build(
            $this->arrayData()
        );

        $this->assertInstanceOf(CreatePostUseCommand::class, $result);
    }

    private function arrayData(): array
    {
        return [
            'user_id' => 1,
            'content' => 'This is a test post content.',
            'media_path' => null,
            'visibility' => 'public',
        ];
    }
}