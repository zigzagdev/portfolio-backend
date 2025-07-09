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

        $userId = User::create($request)->id;

        $postRequest = [
            'content' => 'Vamos Portugal! The team has shown incredible skill and determination.',
            'media_path' => 'https://example.com/media.jpg',
            'visibility' => PostVisibilityEnum::PUBLIC->value,
        ];

        $postId = Post::create(array_merge($postRequest, ['user_id' => $userId]))->id;

        $editRequest = [
            'content' => 'Vamos Portugal! The team has shown incredible skill and determination. Updated content.',
            'media_path' => 'https://example.com/media_updated.jpg',
            'visibility' => PostVisibilityEnum::PRIVATE->value,
        ];

        $response = $this->putJson(
            "api/users/{$userId}/posts/{$postId}",
            $editRequest
        );

        $response->assertJson([
            'status' => 'success',
            'data' => [
                'id' => $postId,
                'user_id' => $userId,
                'content' => $editRequest['content'],
                'media_path' => $editRequest['media_path'],
                'visibility' => PostVisibilityEnum::PRIVATE->toLabel(),
            ]
        ]);
    }
}