<?php

namespace App\Post\Infrastructure\InfrastructureTest;

use App\Common\Application\Dto\Pagination;
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
use Illuminate\Support\Carbon;

class GetAllUserPostQueryServiceTest extends TestCase
{
    private $user;
    private $queryService;
    private $perPage = 15;
    private $currentPage = 1;


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
        $posts = [];
        $baseTime = Carbon::now();

        for ($i = 1; $i <= 50; $i++) {
            $time = $baseTime->copy()->addSeconds($i);

            $posts[] = [
                'user_id' => $this->user->id,
                'content' => "ダミーポスト{$i}",
                'media_path' => $i % 2 === 0 ? null : "https://example.com/image{$i}.jpg",
                'visibility' => $i % 2 === 0 ? 1 : 0,
                'created_at' => $time,
                'updated_at' => $time,
            ];
        }

        Post::insert($posts);
    }

    public function test_check_pagination_type(): void
    {
        $result = $this->queryService->getAllUserPosts(
            $this->user->id,
            $this->perPage,
            $this->currentPage
        );

        $this->assertInstanceOf(
            Pagination::class,
            $result
        );
    }

    public function test_check_pagination_data_value(): void
    {
        $result = $this->queryService->getAllUserPosts(
            $this->user->id,
            $this->perPage,
            $this->currentPage
        );

        foreach ($result->getData() as $post) {
            $this->assertInstanceOf(
                PostEntity::class,
                $post
            );
        }
    }

    public function test_check_pagination_current_page_value(): void
    {
        $result = $this->queryService->getAllUserPosts(
            $this->user->id,
            $this->perPage,
            $this->currentPage = 30
        );

        $this->assertEquals(
            $this->currentPage,
            $result->getCurrentPage()
        );
    }

    public function test_check_pagination_per_page_value(): void
    {
        $result = $this->queryService->getAllUserPosts(
            $this->user->id,
            $this->perPage = 20,
            $this->currentPage
        );

        $this->assertEquals(
            $this->perPage,
            $result->getPerPage()
        );
    }
}