<?php

namespace App\User\Tests;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class User_RegisterTest extends TestCase
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

    public function test_register_user_with_properties_nullable(): void
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

        $response = $this
            ->postJson(
                'api/users/register',
                $request
            );

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_failed_with_invalid_data(): void
    {
        $request = [
            'first_name' => 'Lionel',
            'last_name' => 'Messi',
            'email' => 'barcelona_10@test.com',
            'password' => 'test1234',
            'bio' => 'I am a football player',
            'location' => 'Barcelona',
            'skills' => ['Laravel', 'React'],
            'profile_image' => 'https://example.com/profile.jpg',
        ];

        $response = $this
            ->postJson(
                'api/users/register',
                $request
            );

        $this->assertEquals(201, $response->getStatusCode());
    }
}