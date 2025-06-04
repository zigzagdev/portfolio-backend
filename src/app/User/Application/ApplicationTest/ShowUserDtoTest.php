<?php

namespace App\User\Application\ApplicationTest;

use App\Common\Domain\UserId;
use App\User\Application\Dto\ShowUserDto;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Factory\UserFromModelEntityFactory;
use Tests\TestCase;
use App\User\Domain\ValueObject\Email;
use Mockery;

class ShowUserDtoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function mockData(): array
    {
        return [
            'id' => 1,
            'first_name' => 'xabi',
            'last_name' => 'alonso',
            'bio' => 'I am a football player',
            'email' => 'real-madrid14@test.com',
            'location' => 'Spain',
            'skills' => json_encode(['football', 'coaching']),
            'profile_image' => 'https://example.com/image.jpg',
        ];
    }

    private function mockEntity(): UserEntity
    {
        $factory = Mockery::mock(
            'alias' . UserFromModelEntityFactory::class
        );

        $entity = Mockery::mock(UserEntity::class);

        $factory
            ->shouldReceive('buildFromModel')
            ->andReturn($entity);

        $entity
            ->shouldReceive('getUserId')
            ->andReturn(new UserId($this->mockData()['id']));

        $entity
            ->shouldReceive('getFirstName')
            ->andReturn($this->mockData()['first_name']);

        $entity
            ->shouldReceive('getLastName')
            ->andReturn($this->mockData()['last_name']);

        $entity
            ->shouldReceive('getBio')
            ->andReturn($this->mockData()['bio']);

        $entity
            ->shouldReceive('getEmail')
            ->andReturn(new Email($this->mockData()['email']));

        $entity
            ->shouldReceive('getLocation')
            ->andReturn($this->mockData()['location']);

        $entity
            ->shouldReceive('getSkills')
            ->andReturn(json_decode($this->mockData()['skills'], true));

        $entity
            ->shouldReceive('getProfileImage')
            ->andReturn($this->mockData()['profile_image']);

        return  $entity;
    }

    /**
     * @test
     * @testdox ShowUserDtoTest_build_successfully check type
     */
    public function test1(): void
    {
        $result = ShowUserDto::build(
            $this->mockEntity()
        );
        $this->assertInstanceOf(ShowUserDto::class, $result);
    }

    /**
     * @test
     * @testdox ShowUserDtoTest_build_successfully check value
     */
    public function test2(): void
    {
        $result = ShowUserDto::build(
            $this->mockEntity()
        );

        $this->assertEquals($result->id, new UserId($this->mockData()['id']));
        $this->assertEquals($result->firstName, $this->mockData()['first_name']);
        $this->assertEquals($result->lastName, $this->mockData()['last_name']);
        $this->assertEquals($result->bio, $this->mockData()['bio']);
        $this->assertEquals($result->location, $this->mockData()['location']);
        $this->assertEquals($result->skills, json_decode($this->mockData()['skills'], true));
        $this->assertEquals($result->profileImage, $this->mockData()['profile_image']);
    }
}