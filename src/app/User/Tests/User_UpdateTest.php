<?php

namespace App\User\Tests;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class User_UpdateTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
        $this->user = $this->initialInsert();
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

    private function initialInsert(): User
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

        return $user;
    }

    /**
     * @test
     * @testdox User update test successfully
     */
    public function test1(): void
    {
        $newRequest = [
            'id' => $this->user->id,
            'first_name' => 'Kai',
            'last_name' => 'Havertz',
            'email' => 'arsenal-29@test.com',
            'bio' => 'I am a football player',
            'location' => null,
            'skills' => [],
            'profile_image' => 'https://example.com/profile.jpg',
        ];

        $response = $this->putJson(
            "api/users/{$this->user->id}",
            $newRequest
        );
        $response->assertStatus(JsonResponse::HTTP_OK);
    }

    /**
     * @test
     * @testdox User update test with invalid data
     */
    public function test2(): void
    {
        $newRequest = [
            'id' => $this->user->id,
            'first_name' => 'Kai',
            'last_name' => 'Havertz',
            'bio' => 'I am a football player',
            'email' => 'arsenal-29@test.com',
            'location' => null,
            'skills' => [],
            'profile_image' => 'https://example.com/profile.jpg',
        ];

        $response = $this->putJson(
            "api/users/{$this->user->id}",
            $newRequest
        );

        $fullName = $newRequest['first_name'] . ' ' . $newRequest['last_name'];

        $this->assertNotEquals($this->user->toArray(), $response->getOriginalContent()['data']);
        $this->assertEquals($fullName, $response->getOriginalContent()['data']['full_name']);
        $this->assertEquals($newRequest['email'], $response->getOriginalContent()['data']['email']);
        $this->assertEquals($newRequest['bio'], $response->getOriginalContent()['data']['bio']);
        $this->assertEquals($newRequest['location'], $response->getOriginalContent()['data']['location']);
        $this->assertEquals($newRequest['skills'], $response->getOriginalContent()['data']['skills']);
        $this->assertEquals($newRequest['profile_image'], $response->getOriginalContent()['data']['profile_image']);
    }
}