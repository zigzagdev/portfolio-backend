<?php

namespace App\Post\Presentation\PresentationTest;

use App\Common\Domain\ValueObject\PostId;
use App\Post\Domain\ValueObject\Postvisibility;
use Tests\TestCase;
use App\Post\Application\Dto\CreatePostDto;
use Mockery;
use App\Post\Presentation\ViewModel\CreatePostViewModel;
use App\Common\Domain\ValueObject\UserId;
use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;

class CreatePostViewModelTest extends TestCase
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
            'user_id' => 1,
            'content' => 'This is a test post',
            'media_path' => 'https://example.com/media.jpg',
            'visibility' => 'public',
        ];
    }

    private function mockDto(): CreatePostDto
    {
        $dto = Mockery::mock(CreatePostDto::class);

        $dto
            ->shouldReceive('getId')
            ->andReturn(new PostId($this->arrayData()['id']));

        $dto
            ->shouldReceive('getUserid')
            ->andReturn(new UserId($this->arrayData()['user_id']));

        $dto
            ->shouldReceive('getContent')
            ->andReturn($this->arrayData()['content']);

        $dto
            ->shouldReceive('getMediaPath')
            ->andReturn($this->arrayData()['media_path']);

        $dto
            ->shouldReceive('getVisibility')
            ->andReturn(new Postvisibility(PostVisibilityEnum::fromString($this->arrayData()['visibility'])));

        return $dto;
    }

    public function test1_check_type(): void
    {
        $dto = $this->mockDto();
        $viewModel = new CreatePostViewModel($dto);

        $this->assertInstanceOf(CreatePostViewModel::class, $viewModel);
    }

    public function test2_check_value(): void
    {
        $dto = $this->mockDto();
        $viewModel = new CreatePostViewModel($dto);

        $result = $viewModel->toArray();

        $this->assertEquals($this->arrayData()['id'], $result['id']);
        $this->assertEquals($this->arrayData()['user_id'], $result['userId']);
        $this->assertEquals($this->arrayData()['content'], $result['content']);
        $this->assertEquals($this->arrayData()['media_path'], $result['mediaPath']);
        $this->assertEquals(
            new Postvisibility(PostVisibilityEnum::fromString($this->arrayData()['visibility'])),
            $dto->getVisibility()
        );
    }
}