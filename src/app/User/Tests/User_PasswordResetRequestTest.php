<?php

namespace App\User\Tests;

use App\Models\PasswordResetRequest;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class User_PasswordResetRequestTest extends TestCase
{
    private $endpoint = '/api/users/password-reset';
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
        $this->user = $this->createUser();
    }

    protected function tearDown(): void
    {
        $this->refreshDatabase();
        parent::tearDown();
    }

    private function refreshDatabase(): void
    {
        if (env('APP_ENV') === 'testing') {
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');
            User::truncate();
            PasswordResetRequest::truncate();
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    private function createUser(): User
    {
        $user = User::create([
            'first_name' => 'Lionel',
            'last_name' => 'Messi',
            'email' => "barcelona_".rand(). "@test.com",
            'password' => 'test1234',
            'bio' => 'I am a football player',
            'location' => 'Barcelona',
            'skills' => ['Laravel', 'React'],
            'profile_image' => 'https://example.com/profile.jpg',
        ]);

        return $user;
    }

    public function test_password_reset_request_ok(): void
    {
        $input = $this->user->email;

        $response = $this
            ->postJson(
                $this->endpoint,
                [
                    'email' => $input,
                ]
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas(
            'password_reset_requests',
            [
                'user_id' => $this->user->id,
            ],
            'mysql'
        );
    }
}