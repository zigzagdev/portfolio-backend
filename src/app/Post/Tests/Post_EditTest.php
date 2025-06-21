<?php

namespace App\Post\Tests;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use App\Models\User;
use App\Common\Domain\Enum\PostVisibility as PostVisibilityEnum;

class Post_EditTest extends TestCase
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

    protected function refresh(): void
    {
        if (env('APP_ENV') === 'testing') {
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');
            User::truncate();
            Post::truncate();
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    public function test_feature_test(): void
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
            'content' => 'Vamos Portugal! The team has shown incredible skill and determination.',
            'media_path' => 'https://example.com/media.jpg',
            'visibility' => 'public',
        ];

        $post_id = Post::create(array_merge($postRequest, ['user_id' => $user_id]))->id;

        $editRequest = [
            'content' => 'Vamos Portugal! The team has shown incredible skill and determination. Updated content.',
            'media_path' => 'https://example.com/media_updated.jpg',
            'visibility' => 'private',
        ];

        $response = $this->putJson(
            "api/users/{$user_id}/posts/{$post_id}",
            $editRequest
        );

        $response->assertJson([
            'status' => 'success',
            'data' => [
                'id' => $post_id,
                'user_id' => $user_id,
                'content' => $editRequest['content'],
                'media_path' => $editRequest['media_path'],
                'visibility' => PostVisibilityEnum::PRIVATE->value,
            ]
        ]);
    }
}