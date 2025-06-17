<?php

namespace App\Post\Infrastructure\InfrastructureTest;

use App\Post\Domain\EntityFactory\EditPostEntityFactory;
use App\Post\Infrastructure\Repository\PostRepository;
use Tests\TestCase;
use Mockery;
use App\Post\Domain\Entity\PostEntity;
use App\Post\Domain\EntityFactory\PostFromModelEntityFactory;
use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;
use App\Post\Domain\RepositoryInterface\PostRepositoryInterface;
use App\Common\Domain\ValueObject\PostId;
use App\Common\Domain\ValueObject\UserId;
use App\Post\Domain\ValueObject\Postvisibility;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use App\Models\User;

class EditPostRepositoryTest extends TestCase
{
    private $repository;
    private $userId;
    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
        $this->repository = new PostRepository(
            new Post(),
        );
        $this->userId = $this->createUser();
        $this->createFirstPost();
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
            Post::truncate();
            User::truncate();
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    private function createUser(): int
    {
        return User::create([
            'first_name' => 'bruno',
            'last_name' => 'fernandes',
            'email' => 'manchester-8@test.com',
            'password' => 'test1234',
            'bio' => 'football player',
            'location' => 'Manchester',
            'skills' => ['Laravel', 'React'],
            'profile_image' => 'https://example.com/profile.jpg',
        ])->id;
    }

    private function createFirstPost(): void
    {
        Post::create([
            'user_id' => $this->userId,
            'content' => 'Manchester United wins the Premier League in 2025',
            'media_path' => 'https://example.com/media.jpg',
            'visibility' => 'private',
        ]);
    }

    private function mockEntity(): PostEntity
    {
        $factory = Mockery::mock(
            'alias' . EditPostEntityFactory::class
        );

        $entity = Mockery::mock(PostEntity::class);

        $factory
            ->shouldReceive('build')
            ->andReturn($entity);

        $entity
            ->shouldReceive('getId')
            ->andReturn(new PostId($this->updateData()['id']));

        $entity
            ->shouldReceive('getUserId')
            ->andReturn(new UserId($this->userId));

        $entity
            ->shouldReceive('getContent')
            ->andReturn($this->updateData()['content']);

        $entity
            ->shouldReceive('getMediaPath')
            ->andReturn($this->updateData()['mediaPath']);

        $entity
            ->shouldReceive('getPostVisibility')
            ->andReturn(new Postvisibility(PostVisibilityEnum::fromString($this->updateData()['visibility'])));

        return $entity;
    }

    private function updateData(): array
    {
        $updateData = [
            'id' => 1,
            'userId' => $this->userId,
            'content' => 'Updated content for the post',
            'mediaPath' => 'https://example.com/updated_media.jpg',
            'visibility' => 'public',
        ];

        return $updateData;
    }

    public function test_edit_user_post_check_type(): void
    {
        $result = $this->repository->editById(
            $this->mockEntity()
        );

        $this->assertInstanceOf(PostEntity::class, $result);
    }

    public function test_edit_user_post_check_value(): void
    {
        $result = $this->repository->editById(
            $this->mockEntity()
        );

        $this->assertDatabaseHas(
            'posts',
            [
                'id' => $this->updateData()['id'],
                'user_id' => $this->userId,
                'content' => $this->updateData()['content'],
                'media_path' => $this->updateData()['mediaPath'],
                'visibility' => PostVisibilityEnum::fromString($this->updateData()['visibility'])->toInt(),
            ],
            'mysql'
        );
    }
}