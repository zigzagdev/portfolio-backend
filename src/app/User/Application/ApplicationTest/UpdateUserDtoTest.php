<?php

namespace App\User\Application\ApplicationTest;

use App\User\Application\Dto\UpdateUserDto;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Factory\UserUpdateEntityFactory;
use App\User\Domain\ValueObject\Email;
use Common\Domain\ValueObject\UserId;
use Mockery;
use Tests\TestCase;

class UpdateUserDtoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function mockEntity(): UserEntity
    {
        $factory = Mockery::mock(
            'alias' . UserUpdateEntityFactory::class
        );

        $entity = Mockery::mock(UserEntity::class);

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
            ->andReturn($this->arrayRequestData()['skills']);

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
            'email' => 'manchester-united7@test.com',
            'location' => null,
            'skills' => [],
            'profile_image' => 'https://example.com/profile.jpg',
        ];
    }

    /**
     * @test
     * @testdox UpdateUserDtoTest_build_successfully check type
     */
    public function test1(): void
    {
        $result = UpdateUserDto::build($this->mockEntity());

        $this->assertInstanceOf(UpdateUserDto::class, $result);
    }

    /**
     * @test
     * @testdox UpdateUserDtoTest_build_successfully check value
     */
    public function test2(): void
    {
        $result = UpdateUserDto::build($this->mockEntity());

        $this->assertEquals($result->id, new UserId($this->arrayRequestData()['id']));
        $this->assertEquals($result->firstName, $this->arrayRequestData()['first_name']);
        $this->assertEquals($result->lastName, $this->arrayRequestData()['last_name']);
        $this->assertEquals($result->bio, $this->arrayRequestData()['bio']);
        $this->assertEquals($result->location, $this->arrayRequestData()['location']);
        $this->assertEquals($result->skills, $this->arrayRequestData()['skills']);
        $this->assertEquals($result->profileImage, $this->arrayRequestData()['profile_image']);
    }
}