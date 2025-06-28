<?php

namespace App\User\Infrastructure\InfrastructureTest\Service;

use App\User\Infrastructure\Service\PasswordResetGenerateTokenService;
use Tests\TestCase;
use App\User\Domain\ValueObject\PasswordResetToken;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PasswordResetGenerateTokenServiceTest extends TestCase
{
    private $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
        $this->service  = new PasswordResetGenerateTokenService();
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
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }


    public function test_check_service(): void
    {
        $result = $this->service->generateToken();

        $this->assertInstanceOf(PasswordResetToken::class, $result);
    }

    public function test_check_token_length(): void
    {
        $result = $this->service->generateToken();

        $this->assertEquals(32, strlen($result->getValue()));
    }

    public function test_check_token_too_much_length(): void
    {
        $result = $this->service->generateToken();

        $this->assertLessThanOrEqual(33, strlen($result->getValue()));
    }
}