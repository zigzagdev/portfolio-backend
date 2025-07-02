<?php

namespace App\Post\Application\ApplicationTest;

use App\Post\Application\Dto\GetUserEachPostDto;
use App\Post\Application\UseCase\GetUserEachPostUseCase;
use App\Post\Domain\ValueObject\Postvisibility;
use Tests\TestCase;
use Mockery;
use App\Post\Domain\Entity\PostEntity;
use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;
use App\Common\Domain\ValueObject\PostId;
use App\Common\Domain\ValueObject\UserId;
use App\Post\Application\QueryServiceInterface\GetPostQueryServiceInterface;

class GetUserEachPostUseCaseTest extends TestCase
{
    private $userId;
    protected function setUp(): void
    {
        parent::setUp();
        $this->userId = 1;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function arrayData(): array
    {
        return [
            'id' => 1,
            'userId' => $this->userId,
            'content' => 'Sample post content',
            'mediaPath' => 'path/to/media.jpg',
            'visibility' => 'public',
        ];
    }

    private function mockQueryService(): GetPostQueryServiceInterface
    {
        $mock = Mockery::mock(GetPostQueryServiceInterface::class);

        $mock
            ->shouldReceive('getEachUserPost')
            ->with(
                Mockery::on(fn($v) => $v instanceof UserId && $v->getValue() === 1),
                Mockery::on(fn($v) => $v instanceof PostId && $v->getValue() === 1)
            )
            ->andReturn($this->mockPostEntity());

        return $mock;
    }


    private function mockPostEntity(): PostEntity
    {
        $mock = Mockery::mock(PostEntity::class);

        $mock->shouldReceive('getId')
            ->andReturn(new PostId($this->arrayData()['id']));

        $mock->shouldReceive('getUserId')
            ->andReturn(new UserId($this->arrayData()['userId']));

        $mock->shouldReceive('getContent')
            ->andReturn($this->arrayData()['content']);

        $mock->shouldReceive('getMediaPath')
            ->andReturn($this->arrayData()['mediaPath']);

        $mock->shouldReceive('getPostVisibility')
            ->andReturn(new Postvisibility(
                PostVisibilityEnum::fromString($this->arrayData()['visibility'])
            ));

        return $mock;
    }

    public function test_get_user_each_post(): void
    {
        $useCase = new GetUserEachPostUseCase(
            $this->mockQueryService()
        );

        $result = $useCase->handle(
            $this->userId,
            $this->arrayData()['id']
        );

        $this->assertInstanceOf(GetUserEachPostDto::class, $result);
    }
}