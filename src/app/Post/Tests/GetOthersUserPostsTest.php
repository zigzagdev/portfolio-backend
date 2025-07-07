<?php

namespace App\Post\Tests;

use App\Models\Post;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class GetOthersUserPostsTest extends TestCase
{
    protected $endpoint = "/api/users/{userId}/posts/public";
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

        $this->createDummyPosts($user->id);
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
        for ($userIndex = 1; $userIndex <= 3; $userIndex++) {
            if ($userIndex === 1) {
                continue;
            }

            $user = User::create([
                'first_name' => "User{$userIndex}",
                'last_name' => "Test{$userIndex}",
                'email' => "user{$userIndex}@example.com",
                'password' => bcrypt('password123'),
                'bio' => "This is user {$userIndex}",
                'location' => "City{$userIndex}",
                'skills' => ['Laravel', 'Vue'],
                'profile_image' => 'https://example.com/user.jpg',
            ]);

            for ($i = 1; $i <= 20; $i++) {
                Post::create([
                    'user_id' => $user->id,
                    'content' => "Post {$i} by User{$userIndex}",
                    'media_path' => $i % 2 === 0 ? null : "https://example.com/image{$i}.jpg",
                    'visibility' => $i % 2 === 0 ? 0 : 1,
                    'created_at' => now()->subMinutes(rand(0, 500)),
                    'updated_at' => now(),
                ]);
            }
        }
    }
    public function test_feature_api(): void
    {
        $newEndpoint = str_replace('{userId}', intval($this->userId), $this->endpoint);

        $response = $this->getJson($newEndpoint);

        $data = $response->json('data');
        $meta = $response->json('meta');
        $this->assertEquals(200, $response->status());
        $this->assertCount(15, $data);
        $this->assertEquals(20, $meta['total']);
        $this->assertEquals(1, $meta['currentPage']);
        $this->assertEquals(2, $meta['lastPage']);
        $this->assertEquals(15, $meta['perPage']);
    }
}