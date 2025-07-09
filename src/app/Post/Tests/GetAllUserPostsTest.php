<?php

namespace App\Post\Tests;

use App\Models\Post;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;

class GetAllUserPostsTest extends TestCase
{
    private $endpoint = "/api/users/{userId}/posts";
    private $userId;
    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
        $user = User::create([
            'first_name' => 'Cristiano',
            'last_name' => 'Ronaldo',
            'email' => 'manchester_united7@test.com',
            'password' => 'test1234',
            'bio' => null,
            'location' => null,
            'skills' => ['Laravel', 'React'],
            'profile_image' => null,
        ]);
        $this->userId = $user->id;

        $this->createDummyPosts();
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

    private function createDummyPosts(): void
    {
        $posts = [];

        for ($i = 1; $i <= 50; $i++) {
            $posts[] = [
                'user_id' => $this->userId,
                'content' => "Portugal wins in World cup for {$i}times",
                'media_path' => $i % 2 === 0 ? null : "https://example.com/image{$i}.jpg",
                'visibility' => $i % 2 === 0
                    ? PostVisibilityEnum::PRIVATE->value
                    : PostVisibilityEnum::PUBLIC->value,
                'created_at' => now()->subSeconds(50 - $i),
                'updated_at' => now()->subSeconds(50 - $i),
            ];
        }

        Post::insert($posts);
    }

    public function test_api_routing_is_correct(): void
    {
        $idWithEndpoint = str_replace('{userId}', $this->userId, $this->endpoint);
        $current_page = 1;
        $per_page = 15;

        $response = $this->getJson($idWithEndpoint, [
            'page' => $current_page,
            'per_page' => $per_page,
        ]);

        $response->assertStatus(200);
    }

    public function test_get_all_user_posts_check_value(): void
    {
        $idWithEndpoint = str_replace('{userId}', $this->userId, $this->endpoint);
        $current_page = 1;
        $per_page = 15;

        $response = $this->getJson($idWithEndpoint, [
            'page' => $current_page,
            'per_page' => $per_page,
        ]);

        foreach ($response->json('data') as $post) {
            $this->assertArrayHasKey('id', $post);
            $this->assertArrayHasKey('userId', $post);
            $this->assertArrayHasKey('content', $post);
            $this->assertArrayHasKey('mediaPath', $post);
            $this->assertArrayHasKey('visibility', $post);
        }
    }

    public function test_get_all_user_posts_with_invalid_user_id(): void
    {
        $invalidUserId = 9999;
        $idWithEndpoint = str_replace('{userId}', $invalidUserId, $this->endpoint);

        $response = $this->getJson($idWithEndpoint);

        $response->assertStatus(500);
    }
}