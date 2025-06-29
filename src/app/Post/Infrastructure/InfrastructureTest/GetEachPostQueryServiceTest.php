<?php

namespace App\Post\Infrastructure\InfrastructureTest;

use App\Post\Domain\Entity\PostEntity;
use App\Post\Domain\ValueObject\Postvisibility;
use App\Post\Infrastructure\QueryService\GetPostQueryService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Common\Domain\ValueObject\UserId;
use App\Common\Domain\ValueObject\PostId;
use App\Post\Application\QueryServiceInterface\GetPostQueryServiceInterface;
use App\Models\Post;
use App\Models\User;
use Mockery;
use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;

class GetEachPostQueryServiceTest extends TestCase
{
    private GetPostQueryServiceInterface $queryService;
    private $user;
    private $post;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
        $this->user = User::create(([
            'first_name' => 'Sergio',
            'last_name' => 'Ramos',
            'email' => 'real-madrid15@test.com',
            'password' => 'el-capitán-1234',
            'bio' => 'Real Madrid player',
            'location' => 'Madrid',
            'skills' => ['Football', 'Leadership'],
            'profile_image' => 'https://example.com/sergio.jpg'
        ]));

        $this->post = $this->createPostData();
        $this->queryService = new GetPostQueryService(
            $this->post,
            $this->user
        );
    }

    protected function tearDown(): void
    {
        $this->refresh();
        parent::tearDown();
    }

    private function createPostData(): Post
    {
        $post = Post::create([
            'user_id' => $this->user->id,
            'content' => 'Cristiano Ronaldo is a legendary footballer known for his incredible skills and goal-scoring ability.',
            'media_path' => 'https://example.com/media.jpg',
            'visibility' => 'public',
        ]);

        return $post;
    }

    private function refresh(): void
    {
        if (env('APP_ENV') === 'testing') {
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');
            User::truncate();
            Post::truncate();
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    private function mockEntity(): PostEntity
    {
        $mock = Mockery::mock(PostEntity::class);

        $mock
            ->shouldReceive('getId')
            ->andReturn(new PostId($this->post->id));

        $mock
            ->shouldReceive('getUserId')
            ->andReturn(new UserId($this->user->id));

        $mock
            ->shouldReceive('getContent')
            ->andReturn($this->createPostData()['content']);

        $mock
            ->shouldReceive('getMediaPath')
            ->andReturn($this->createPostData()['media_path']);

        $mock
            ->shouldReceive('getPostVisibility')
            ->andReturn(new Postvisibility(
                PostVisibilityEnum::from($this->createPostData()['visibility'])
            ));

        return $mock;
    }

    public function test_check_method_return_type(): void
    {
        $result = $this->queryService->getEachUserPost(
            new UserId($this->user->id),
            new PostId($this->post->id)
        );

        $this->assertInstanceOf(
            PostEntity::class,
            $result
        );
    }

    public function test_check_method_return_value(): void
    {
        $result = $this->queryService->getEachUserPost(
            new UserId($this->user->id),
            new PostId($this->post->id)
        );

        $this->assertEquals(
            $this->mockEntity()->getId(),
            $result->getId()
        );

        $this->assertEquals(
            $this->mockEntity()->getUserId(),
            $result->getUserId()
        );

        $this->assertEquals(
            $this->mockEntity()->getContent(),
            $result->getContent()
        );

        $this->assertEquals(
            $this->mockEntity()->getMediaPath(),
            $result->getMediaPath()
        );

        $this->assertEquals(
            $this->mockEntity()->getPostVisibility(),
            $result->getPostVisibility()
        );
    }
}