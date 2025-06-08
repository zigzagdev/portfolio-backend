<?php

namespace App\Post\Infrastructure\InfrastructureTest;

use App\Post\Domain\Entity\PostEntity;
use App\Post\Domain\ValueObject\Postvisibility;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Post\Infrastructure\Repository\PostRepository;
use Mockery;
use App\Common\Domain\ValueObject\Userid;
use App\Common\Domain\ValueObject\PostId;
use Illuminate\Support\Facades\DB;
use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;

class PostRepositoryTest extends TestCase
{
    private $user;
    private $post;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
        $this->user = new User();
        $this->post = new Post();
        $this->repository = new PostRepository(
            $this->post
        );
    }

    protected function tearDown(): void
    {
        $this->refresh();
        parent::tearDown();
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

    private function arrayUserData(): array
    {
        return [
            'first_name' => 'bruno',
            'last_name' => 'fernandes',
            'email' => 'manchester-8@test.com',
            'password' => 'test1234',
            'bio' => 'football player',
            'location' => 'Manchester',
            'skills' => ['Laravel', 'React'],
            'profile_image' => 'https://example.com/profile.jpg',
        ];
    }

    private function arrayPostData(
        int $userId
    ): array
    {
        return [
            'user_id' => $userId,
            'content' => 'bruno fernandes post content',
            'media_path' => 'https://example.com/media.jpg',
            'visibility' => 'public',
        ];
    }

    private function mockEntity(int $userId, array $postData): PostEntity
    {
        $entity = Mockery::mock(PostEntity::class);

        $entity->shouldReceive('getId')->andReturn(new PostId($this->post->id));
        $entity->shouldReceive('getUserId')->andReturn(new Userid($userId));
        $entity->shouldReceive('getContent')->andReturn($postData['content']);
        $entity->shouldReceive('getMediaPath')->andReturn($postData['media_path']);
        $entity->shouldReceive('getPostVisibility')->andReturn(
            new Postvisibility(PostVisibilityEnum::fromString($postData['visibility']))
        );

        return $entity;
    }

    public function test_check_value(): void
    {
        $user = $this->user->create($this->arrayUserData());

        $postData = $this->arrayPostData($user->id);

        $postEntity = $this->mockEntity($user->id, $postData);

        $this->repository->save($postEntity);

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'content' => $postData['content'],
            'media_path' => $postData['media_path'],
            'visibility' => PostVisibilityEnum::fromString($postData['visibility'])->toInt(),
        ], 'mysql');
    }
}