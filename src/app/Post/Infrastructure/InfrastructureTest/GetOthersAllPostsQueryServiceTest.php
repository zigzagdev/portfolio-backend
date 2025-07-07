<?php

namespace App\Post\Infrastructure\InfrastructureTest;

use App\Post\Domain\Entity\PostEntity;
use App\Post\Infrastructure\QueryService\GetPostQueryService;
use Tests\TestCase;
use App\Post\Application\QueryServiceInterface\GetPostQueryServiceInterface;
use App\Common\Application\DtoFactory\PaginationFactory;
use App\Common\Application\Dto\Pagination as PaginationDto;
use Mockery;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Post;

class GetOthersAllPostsQueryServiceTest extends TestCase
{

    private $user;
    private int $currentPage = 1;
    private int $perPage = 10;
    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
        $this->user = $this->createUsersWithPosts();
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

    private function createUsersWithPosts(): User
    {
        $users = [];

        for ($i = 1; $i <= 3; $i++) {
            $user = User::create([
                'first_name' => "User{$i}",
                'last_name' => "Test{$i}",
                'email' => "user{$i}@example.com",
                'password' => bcrypt('password123'),
                'bio' => "This is user {$i}",
                'location' => "City{$i}",
                'skills' => ['Laravel', 'Vue'],
                'profile_image' => 'https://example.com/user.jpg',
            ]);

            $visibility = $i % 2 === 0 ? 0 : 1;
            for ($j = 1; $j <= 20; $j++) {
                Post::create([
                    'user_id' => $user->id,
                    'content' => "Post {$j} by User{$i}",
                    'media_path' => null,
                    'visibility' => $visibility,
                    'created_at' => now()->subMinutes(rand(0, 1000)),
                    'updated_at' => now(),
                ]);
            }

            $users[] = $user;
        }

        return $users[0];
    }

    public function test_check_query_service_return_type(): void
    {
        $queryService = new GetPostQueryService(
            new Post(),
            new User(),
        );

        $result = $queryService->getOthersAllPosts(
            $this->user->id,
            $this->perPage,
            $this->currentPage,
        );

        $this->assertInstanceOf(
            PaginationDto::class,
            $result
        );
    }

    public function test_check_query_service_return_value(): void
    {
        $queryService = new GetPostQueryService(
            new Post(),
            new User(),
        );

        $result = $queryService->getOthersAllPosts(
            $this->user->id,
            $this->perPage,
            $this->currentPage,
        );

        $this->assertEquals($this->currentPage, $result->getCurrentPage());
        $this->assertEquals($this->perPage, $result->getPerPage());
        foreach ($result->getData() as $post) {
            $this->assertInstanceOf(
                PostEntity::class,
                $post
            );
        }
    }
}