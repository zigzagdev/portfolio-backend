<?php

namespace App\User\Tests;

use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class User_ShowTest extends TestCase
{
    private  $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
        $this->user = new User();
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

    public function test_success_with_nullable_properties(): void
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

        $userId = $user->id;

        $response = $this->getJson("api/users/show/{$userId}");
        $this->assertInstanceOf(TestResponse::class, $response);
    }

    public function test_failed_with_invalid_property(): void
    {
        $user = User::create([
            'first_name' => 'Cristiano',
            'last_name' => 'Ronaldo',
            'email' => 'real-madrid7@test.com',
            'password' => 'test1234',
            'bio' => 'I am a football player',
            'location' => 'Madrid',
            'skills' => ['Laravel', 'React'],
            'profile_image' => 'https://example.com/profile.jpg',
        ]);

        $userId = $user->id;

        $response = $this->getJson("api/users/show/{$userId}");

        $this->assertEquals(200, $response->getStatusCode());

        $arrayResponse = $response->getOriginalContent()['data'];

        $fullName = $user->first_name . ' ' . $user->last_name;
        $this->assertEquals($user->id, $arrayResponse['id']);
        $this->assertEquals($fullName, $arrayResponse['full_name']);
        $this->assertEquals($user->email, $arrayResponse['email']);
        $this->assertEquals($user->bio, $arrayResponse['bio']);
        $this->assertEquals($user->location, $arrayResponse['location']);
        $this->assertEquals(json_encode($user->skills), $arrayResponse['skills']);
        $this->assertEquals($user->profile_image, $arrayResponse['profile_image']);
    }
}