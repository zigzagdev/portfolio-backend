<?php

namespace App\User\Infrastructure\InfrastructureTest\Service;

use App\Models\PasswordResetRequest;
use App\Models\User;
use App\User\Infrastructure\Service\PasswordResetTokenValidatorService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PasswordResetTokenValidatorServiceTest extends TestCase
{
    private $service;
    private $user;
    private $resetRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
        $this->service = new PasswordResetTokenValidatorService();
        $this->user = $this->createUser();
        $this->resetRequest = $this->createResetRequest();
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
        return User::Create([
            'first_name' => 'Sergio',
            'last_name' => 'Ramos',
            'email' => "real-madrid".rand(). "@test.com",
            'password' => 'el-capitán-1234',
            'bio' => 'Real Madrid player',
            'location' => 'Madrid',
            'skills' => ['Football', 'Leadership'],
            'profile_image' => 'https://example.com/sergio.jpg'
        ]);
    }

    private function createResetRequest(): PasswordResetRequest
    {
        return PasswordResetRequest::create([
            'user_id' => $this->user->id,
            'token' => bin2hex(random_bytes(32)),
            'requested_at' => now(),
            'expired_at' => now()->addMinutes(30),
        ]);
    }

    public function test_check_token_validate(): void
    {
        $this->expectNotToPerformAssertions();

        $this->service->validate(
            $this->user->id,
            $this->resetRequest->token
        );
    }
}