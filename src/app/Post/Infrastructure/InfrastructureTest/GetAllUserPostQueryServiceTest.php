<?php

namespace App\Post\Infrastructure\InfrastructureTest;

use App\Post\Domain\Entity\PostEntity;
use App\Post\Infrastructure\QueryService\GetAllUserPostQueryService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Post\Application\QueryServiceInterface\GetAllUserPostQueryServiceInterface;
use App\Post\Domain\Entity\PostEntityCollection;
use Mockery;
use App\Post\Domain\EntityFactory\PostFromModelEntityFactory;
use App\Models\Post;
use App\Models\User;
use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;
use App\Common\Domain\ValueObject\UserId;
use App\Common\Domain\ValueObject\PostId;
use App\Post\Domain\ValueObject\Postvisibility;

class GetAllUserPostQueryServiceTest extends TestCase
{
    private $user;
    private $queryService;

    private $post;
    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
        $this->user = User::create([
            'first_name' => 'Sergio',
            'last_name' => 'Ramos',
            'email' => 'real-madrid15@test.com',
            'password' => 'el-capitán-1234',
            'bio' => 'Real Madrid player',
            'location' => 'Madrid',
            'skills' => ['Football', 'Leadership'],
            'profile_image' => 'https://example.com/sergio.jpg'
        ]);
        $this->post = new Post();
        $this->createDummyPosts();

        $this->queryService = new GetAllUserPostQueryService($this->post);
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

    private function createDummyPosts(): void
    {
        Post::insert([
            [
                'user_id' => $this->user->id,
                'content' => 'ダミーポスト1',
                'media_path' => 'https://example.com/image1.jpg',
                'visibility' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $this->user->id,
                'content' => 'ダミーポスト2',
                'media_path' => null,
                'visibility' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    private function mockEntityCollection(): PostEntityCollection
    {
        $entityCollection = Mockery::mock(PostEntityCollection::class);

        $entityCollection
            ->shouldReceive('getAll')
            ->andReturn($this->arrayTestData());

        $entityCollection
            ->shouldReceive('build')
            ->andReturnUsing(function ($data) {
                return Mockery::mock(PostFromModelEntityFactory::class)
                    ->shouldReceive('build')
                    ->with($data)
                    ->andReturn(Mockery::mock(PostFromModelEntityFactory::class));
            });

        return $entityCollection;
    }

    private function mockEntity(): PostEntity
    {
        $entity = Mockery::mock(PostEntity::class);

        $entity
            ->shouldReceive('getId')
            ->andReturn($this->arrayTestData()[0]['id']);

        $entity
            ->shouldReceive('getMediaPath')
            ->andReturn($this->arrayTestData()[0]['media_path']);

        $entity
            ->shouldReceive('getContent')
            ->andReturn($this->arrayTestData()[0]['content']);

        $entity
            ->shouldReceive('getUserId')
            ->andReturn($this->arrayTestData()[0]['user_id']);

        $entity
            ->shouldReceive('getPostVisibility')
            ->andReturn(PostVisibilityEnum::from($this->arrayTestData()[0]['visibility']));

        return $entity;
    }

    private function arrayTestData(): array
    {
        return [
            [
                'id' => 1,
                'user_id' => $this->user->id,
                'content' => 'Test post content',
                'media_path' => 'https://example.com/media/test.jpg',
                'visibility' => PostVisibilityEnum::PUBLIC->value
            ],
            [
                'id' => 2,
                'user_id' => $this->user->id,
                'content' => 'Another test post content',
                'media_path' => null,
                'visibility' => PostVisibilityEnum::PRIVATE->value
            ]
        ];
    }

    private function mockPostFromEntity(): PostEntity
    {
        $factory = Mockery::mock(
            'alias' . PostFromModelEntityFactory::class
        );

        $entity = Mockery::mock(PostEntity::class);

        $factory
            ->shouldReceive('buildFromModel')
            ->andReturn($entity);

        $entity
            ->shouldReceive('getId')
            ->andReturn(new PostId($this->arrayTestData()[0]['id']));

        $entity
            ->shouldReceive('getUserId')
            ->andReturn(new UserId($this->arrayTestData()[0]['user_id']));

        $entity
            ->shouldReceive('getContent')
            ->andReturn($this->arrayTestData()[0]['content']);

        $entity
            ->shouldReceive('getMediaPath')
            ->andReturn($this->arrayTestData()[0]['media_path']);

        $entity
            ->shouldReceive('getPostVisibility')
            ->andReturn(new Postvisibility(
                PostVisibilityEnum::from((int) $this->arrayTestData()[0]['visibility'])
            ));

        return $entity;
    }

    public function test_get_all_post_check_type(): void
    {
        $result = $this->queryService->getAllUserPosts($this->user->id);

        $this->assertInstanceOf(PostEntityCollection::class, $result);
    }

    public function test_get_all_post_check_value(): void
    {
        $result = $this->queryService->getAllUserPosts($this->user->id);

        $this->assertCount(2, $result->getPosts());
    }
}