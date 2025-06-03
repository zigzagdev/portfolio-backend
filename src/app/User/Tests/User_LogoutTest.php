<?php

namespace App\User\Tests;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Config;



class User_LogoutTest extends TestCase
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
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    public function test1(): void
    {
        Config::set('jwt.secret', 'test-secret-123'); // ← 明示的に上書き

        $user = User::create([
            'first_name' => 'Cristiano',
            'last_name' => 'Ronaldo',
            'email' => 'manchester_united7@test.com',
            'password' => bcrypt('test1234'),
            'bio' => null,
            'location' => null,
            'skills' => ['Laravel', 'React'],
            'profile_image' => null,
        ]);

        $payload = [
            'user_id' => $user->id,
            'iat' => now()->timestamp,
            'exp' => now()->addHour()->timestamp,
        ];

        $secret = config('jwt.secret');
        $token = JWT::encode($payload, $secret, 'HS256');

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'User logged out successfully',
            ]);
    }
}