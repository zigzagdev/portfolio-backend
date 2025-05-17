<?php

namespace App\User\Application\ApplicationTest;

use Tests\TestCase;
use App\User\Application\Factory\RegisterUserDtoFactory;
use App\User\Application\Dto\RegisterUserDto;
use App\User\Domain\ValueObject\Email;
use App\Common\Domain\UserId;
use App\Models\User;
use Mockery;

class RegisterUserDtoFactoryTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     * @testdox RegisterUserDtoFactoryTest_build_successfully check type
     */
    public function test1(): void
    {
        $testData = $this->mockUser();
        $result = RegisterUserDtoFactory::build($testData);

        $this->assertInstanceOf(RegisterUserDto::class, $result);
    }

    /**
     * @test
     * @testdox RegisterUserDtoFactoryTest_build_successfully check value
     */
    public function test2(): void
    {
        $testData = $this->mockUser();
        $result = RegisterUserDtoFactory::build($testData);

        $this->assertEquals($result->id, new UserId($this->arrayRequestData()['id']));
        $this->assertEquals($result->firstName, $this->arrayRequestData()['first_name']);
        $this->assertEquals($result->lastName, $this->arrayRequestData()['last_name']);
        $this->assertEquals($result->email, new Email($this->arrayRequestData()['email']));
        $this->assertEquals($result->bio, $this->arrayRequestData()['bio']);
        $this->assertEquals($result->location, $this->arrayRequestData()['location']);
        $this->assertEquals($result->skills, json_decode($this->arrayRequestData()['skills'], true));
        $this->assertEquals($result->profileImage, $this->arrayRequestData()['profile_image']);
    }

    private function mockUser(): User
    {
        $mockUser = Mockery::mock(User::class);

        $mockUser
            ->shouldReceive('toArray')
            ->andReturn($this->arrayRequestData());

        return $mockUser;
    }

    private function arrayRequestData(): array
    {
        return [
            'id' => 1,
            'first_name' => 'Zlatan',
            'last_name' => 'Ibrahimovic',
            'email' => 'ac-milan@test.com',
            'bio' => 'I am a football player',
            'location' => 'Milan',
            'skills' => json_encode(['Laravel', 'React']),
            'profile_image' => 'https://example.com/profile.jpg',
        ];
    }
}