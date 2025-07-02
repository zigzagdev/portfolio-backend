<?php

namespace App\User\Tests;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\PasswordResetRequest;
use Carbon\Carbon;

class User_PasswordResetTest extends TestCase
{
    private $endpoint = '/api/users/password-reset/confirm';
    private $user;
    private $token;
    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
        $this->user = $this->createUser();
        $this->token = bin2hex(random_bytes(32));
        $this->createRequest();
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
            PasswordResetRequest::truncate();
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    private function createUser(): User
    {
        return User::create([
            'first_name' => 'Lionel',
            'last_name' => 'Messi',
            'email' => "barcelona_".rand(). "@test.com",
            'password' => 'test1234',
            'bio' => 'I am a football player',
            'location' => 'Barcelona',
            'skills' => ['Laravel', 'React'],
            'profile_image' => 'https://example.com/profile.jpg',
        ]);
    }

    private function createRequest(): void
    {
        PasswordResetRequest::create([
            'user_id' => $this->user->id,
            'token' => $this->token,
            'requested_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'expired_at' => Carbon::now()->addMinutes(60),
        ]);
    }

    public function test_password_reset_confirm_ok(): void
    {
        $input = [
            'token' => $this->token,
            'new_password' => 'new-password-1234',
        ];

        $response = $this
            ->postJson(
                $this->endpoint,
                $input
            );

        $response
            ->assertStatus(200);
    }
}