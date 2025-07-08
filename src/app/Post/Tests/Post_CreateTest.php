<?php

namespace App\Post\Tests;

use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;
use App\Models\Post;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class Post_CreateTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
    }

    protected function tearDown(): void
    {
        $this->refresh();
        parent::tearDown();
    }

    protected function refresh()
    {
        if (env('APP_ENV') === 'testing') {
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');
            User::truncate();
            Post::truncate();
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    public function test_create_post(): void
    {
        $request = [
            'first_name' => 'Cristiano',
            'last_name' => 'Ronaldo',
            'email' => 'manchester_united7@test.com',
            'password' => 'test1234',
            'bio' => null,
            'location' => null,
            'skills' => ['Laravel', 'React'],
            'profile_image' => null,
        ];

        $user_id = User::create($request)->id;

        $postRequest = [
            'title' => 'Portugal Wins Nations League in 2025',
            'content' => 'Vamos Portugal! The team has shown incredible skill and determination.',
            'media_path' => 'https://example.com/media.jpg',
            'visibility' => PostVisibilityEnum::PUBLIC->value,
        ];

        $response = $this->post(
            "api/users/{$user_id}/posts",
            $postRequest
        );

        $this->assertEquals(201, $response->getStatusCode());
    }
}