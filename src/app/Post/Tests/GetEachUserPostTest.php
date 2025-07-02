<?php

namespace App\Post\Tests;

use App\Models\Post;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class GetEachUserPostTest extends TestCase
{
    private $endpoint = "/api/users/{userId}/posts/{postId}";
    private $userId;
    private $postId;
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
        $this->endpoint = str_replace('{userId}', $this->userId, $this->endpoint);

        $this->postId = $this->createDummyPosts();
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

    private function createDummyPosts(): int
    {
        $posts = [];

        for ($i = 1; $i <= 10; $i++) {
            $posts[] = [
                'user_id' => $this->userId,
                'content' => "Portugal wins in World cup for {$i}times",
                'media_path' => $i % 2 === 0 ? null : "https://example.com/image{$i}.jpg",
                'visibility' => $i % 2 === 0 ? 1 : 0,
                'created_at' => now()->subSeconds(10 - $i),
                'updated_at' => now()->subSeconds(10 - $i),
            ];
        }

        Post::insert($posts);

        return Post::where('user_id', $this->userId)->first()->id;
    }

    public function test_api_routing_is_correct(): void
    {
        $newEndPoint = str_replace('{postId}', $this->postId, $this->endpoint);

        $response = $this->getJson($newEndPoint);

        $this->assertEquals(200, $response->status());
    }

    public function test_get_each_user_post(): void
    {
        $newEndPoint = str_replace('{postId}', $this->postId, $this->endpoint);

        $response = $this->getJson($newEndPoint);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'userId',
                    'content',
                    'mediaPath',
                    'visibility',
                ],
            ]);
    }
}