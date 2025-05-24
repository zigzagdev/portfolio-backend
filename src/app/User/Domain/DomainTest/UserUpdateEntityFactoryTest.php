<?php

namespace App\User\Domain\DomainTest;

use App\Models\User;
use Tests\TestCase;
use App\User\Domain\Entity\UserEntity;
use App\Common\Domain\UserId;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\Factory\UserUpdateEntityFactory;

class UserUpdateEntityFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }


    private function arrayRequestData(): array
    {
        return [
            'id' => 1,
            'first_name' => 'Steaven',
            'last_name' => 'Gerrard',
            'email' => 'liverpool-8@test.com',
            'bio' => 'I am a football player',
            'location' => 'Liverpool',
            'skills' => ['Laravel', 'React'],
            'profile_image' => 'https://example.com/profile.jpg',
        ];
    }

    /**
     * @test
     * @testdox UserUpdateEntityFactory_build_successfully check type
     */
    public function test1(): void
    {
        $result = UserUpdateEntityFactory::build($this->arrayRequestData());

        $this->assertInstanceOf(UserEntity::class, $result);
    }

    /**
     * @test
     * @testdox UserUpdateEntityFactory_build_successfully check value
     */
    public function test2(): void
    {
        $result = UserUpdateEntityFactory::build($this->arrayRequestData());

        $this->assertEquals($result->getFirstName(), $this->arrayRequestData()['first_name']);
        $this->assertEquals($result->getLastName(), $this->arrayRequestData()['last_name']);
        $this->assertEquals($result->getEmail()->getValue(), $this->arrayRequestData()['email']);
        $this->assertEquals($result->getBio(), $this->arrayRequestData()['bio']);
        $this->assertEquals($result->getLocation(), $this->arrayRequestData()['location']);
        $this->assertEquals($result->getSkills(), $this->arrayRequestData()['skills']);
        $this->assertEquals($result->getProfileImage(), $this->arrayRequestData()['profile_image']);
        $this->assertEquals($result->getUserId()->getValue(), $this->arrayRequestData()['id']);
    }

    /**
     * @test
     * @testdox UserUpdateEntityFactory_build_successfully check partially update data
     */
    public function test3(): void
    {
        $partiallyData = [
            'id' => 1,
            'first_name' => 'Didier',
            'last_name' => 'Drogba',
            'email' => 'chelsea-11@test.com',
            'location' => 'london',
            'skills' => [],
            'profile_image' => 'https://example.com/profile.jpg',
        ];

        $result = UserUpdateEntityFactory::build($partiallyData);

        $this->assertEquals($result->getFirstName(), $partiallyData['first_name']);
        $this->assertEquals($result->getLastName(), $partiallyData['last_name']);
        $this->assertEquals($result->getEmail()->getValue(), $partiallyData['email']);
        $this->assertEquals($result->getLocation(), $partiallyData['location']);
        $this->assertEquals($result->getProfileImage(), $partiallyData['profile_image']);
        $this->assertEquals($result->getSkills(), $partiallyData['skills']);
        $this->assertEquals($result->getUserId()->getValue(), $partiallyData['id']);
    }
}