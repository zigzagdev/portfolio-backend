<?php

namespace App\User\Infrastructure\InfrastructureTest\Service;

use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\User;
use App\Models\PasswordResetRequest;
use App\User\Infrastructure\Service\ThrottlePasswordResetRequestService;

class ThrottlePasswordResetRequestServiceTest extends TestCase
{
    private $user;
    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
        $this->user = $this->createUser();
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

    public function test_throttle_password_request_correct_request(): void
    {
        for ($i = 0; $i < 3; $i++) {
            PasswordResetRequest::create([
                'user_id' => $this->user->id,
                'token' => 'token-' . $i,
                'requested_at' => now()->subMinutes(10),
                'expired_at' => now()->addHour(),
                'created_at' => now()->subMinutes(10),
                'updated_at' => now()->subMinutes(10),
            ]);
        }


        $service = new ThrottlePasswordResetRequestService();

        $this->expectNotToPerformAssertions();
        $service->checkThrottling($this->user);
    }

    public function test_throttle_password_request_incorrect_request(): void
    {
        for ($i = 0; $i < 6; $i++) {
            PasswordResetRequest::create([
                'user_id' => $this->user->id,
                'token' => 'token-' . $i,
                'requested_at' => now()->subMinutes(10),
                'expired_at' => now()->addHour(),
                'created_at' => now()->subMinutes(10),
                'updated_at' => now()->subMinutes(10),
            ]);
        }

        $this->expectException(TooManyRequestsHttpException::class);
        $service = new ThrottlePasswordResetRequestService();

        $service->checkThrottling($this->user);
    }

    public function test_throttle_password_request_wrong_user(): void
    {
        for ($i = 0; $i < 3; $i++) {
            PasswordResetRequest::create([
                'user_id' => $this->user->id,
                'token' => 'token-' . $i,
                'requested_at' => now()->subMinutes(10),
                'expired_at' => now()->addHour(),
                'created_at' => now()->subMinutes(10),
                'updated_at' => now()->subMinutes(10),
            ]);
        }

        $wrongUser = $this->createUser();

        $service = new ThrottlePasswordResetRequestService();

        $this->expectNotToPerformAssertions();

        $service->checkThrottling($wrongUser);
    }
}