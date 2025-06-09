<?php

namespace App\User\Application\ApplicationTest;

use App\User\Application\Dto\RegisterUserDto;
use App\User\Application\Factory\RegisterUserDtoFactory;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Factory\UserEntityFactory;
use App\User\Domain\ValueObject\Email;
use App\Common\Domain\ValueObject\UserId;
use Mockery;
use Tests\TestCase;

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
        $testData = $this->mockEntity();
        $result = RegisterUserDtoFactory::build($testData);

        $this->assertInstanceOf(RegisterUserDto::class, $result);
    }

    /**
     * @test
     * @testdox RegisterUserDtoFactoryTest_build_successfully check value
     */
    public function test2(): void
    {
        $testData = $this->mockEntity();
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

    private function mockEntity(): UserEntity
    {
        $factory = Mockery::mock(
            'alias'. UserEntityFactory::class
        );

        $entity = Mockery::mock(
            UserEntity::class
        );

        $factory
            ->shouldReceive('build')
            ->andReturn($entity);

        $entity
            ->shouldReceive('getUserId')
            ->andReturn(new UserId($this->arrayRequestData()['id']));

        $entity
            ->shouldReceive('getFirstName')
            ->andReturn($this->arrayRequestData()['first_name']);

        $entity
            ->shouldReceive('getLastName')
            ->andReturn($this->arrayRequestData()['last_name']);

        $entity
            ->shouldReceive('getEmail')
            ->andReturn(new Email($this->arrayRequestData()['email']));

        $entity
            ->shouldReceive('getBio')
            ->andReturn($this->arrayRequestData()['bio']);

        $entity
            ->shouldReceive('getLocation')
            ->andReturn($this->arrayRequestData()['location']);

        $entity
            ->shouldReceive('getSkills')
            ->andReturn(json_decode($this->arrayRequestData()['skills'], true));

        $entity
            ->shouldReceive('getProfileImage')
            ->andReturn($this->arrayRequestData()['profile_image']);

        return $entity;
    }

    private function arrayRequestData(): array
    {
        return [
            'id' => 1,
            'first_name' => 'Cristiano',
            'last_name' => 'Ronaldo',
            'bio' => 'I am a football player',
            'email' => 'manchester-united@test.com',
            'skills' => json_encode(['Laravel', 'React']),
            'location' => 'Manchester',
            'profile_image' => 'https://example.com/profile.jpg',
        ];
    }
}