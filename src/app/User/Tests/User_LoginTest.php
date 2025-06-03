<?php

namespace App\User\Tests;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class User_LoginTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->refreshDatabase();
    }

    private function refreshDatabase(): void
    {
        if (env('APP_ENV') === 'testing') {
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');
            User::truncate();
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * @test
     * @testdox User registration test successfully (some properties are null)
     */
    public function test1(): void
    {
        $user = User::create([
            'first_name' => 'Lionel',
            'last_name' => 'Messi',
            'email' => 'barcelona_10@test.com',
            'password' => 'test1234',
            'bio' => 'I am a football player',
            'location' => 'Barcelona',
            'skills' => ['Laravel', 'React'],
            'profile_image' => 'https://example.com/profile.jpg',
        ]);

        $request = [
            'email' => 'barcelona_10@test.com',
            'password' => 'test1234',
        ];

        $response = $this
            ->postJson(
                'api/users/login',
                $request
            );

        $this->assertEquals(200, $response->getStatusCode());
    }
}