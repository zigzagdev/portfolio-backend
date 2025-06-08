<?php

namespace App\Post\Domain\DomainTest;

use App\Post\Domain\EntityFactory\PostFromModelEntityFactory;
use Tests\TestCase;
use App\Post\Domain\Entity\PostEntity;

class PostFromModelEntityFactoryTest extends TestCase
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
            'id' => 2,
            'userId' => 1,
            'content' => 'yield from eloquent model',
            'mediaPath' => 'https://example.com/media.jpg',
            'visibility' => 'public'
        ];
    }

    public function test_build_entity_from_factory_successfully_check_type(): void
    {
        $result = PostFromModelEntityFactory::build($this->arrayData());

        $this->assertInstanceOf(PostEntity::class, $result);
    }

    public function test_build_entity_from_factory_successfully_check_data(): void
    {
        $result = PostFromModelEntityFactory::build($this->arrayData());

        $this->assertEquals($this->arrayData()['id'], $result->getId()->getValue());
        $this->assertEquals($this->arrayData()['userId'], $result->getUserId()->getValue());
        $this->assertEquals($this->arrayData()['content'], $result->getContent());
        $this->assertEquals($this->arrayData()['mediaPath'], $result->getMediaPath());
        $this->assertEquals($this->arrayData()['visibility'], $result->getPostVisibility()->getStringValue());
    }
}