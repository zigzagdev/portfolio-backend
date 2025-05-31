<?php

namespace App\User\Domain\Factory;

use Tests\TestCase;
use Mockery;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use App\User\Domain\Factory\UserLoginEntityFactory;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\ValueObject\Email;

class UserLoginEntityFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function mockPasswordHasher(): PasswordHasherInterface
    {
        $hasher = Mockery::mock(PasswordHasherInterface::class);

        $hasher
            ->shouldReceive('hash')
            ->andReturn($this->arrayData()['password']);

        return $hasher;
    }

    private function arrayData(): array
    {
        return [
            'first_name' => '',
            'last_name' => '',
            'email' => 'argentina-10@test.com',
            'password' => 'test1234',
            'bio' => null,
            'location' => null,
            'skills' => [],
            'profile_image' => null,
        ];
    }

    /**
     * @test
     * @testdox UserLoginEntityFactory should build a UserEntity with correct data
     */
    public function test1(): void
    {
        $result = UserLoginEntityFactory::build(
            $this->arrayData(),
            $this->mockPasswordHasher()
        );

        $this->assertInstanceOf(UserEntity::class, $result);
    }

    /**
     * @test
     * @testdox UserLoginEntityFactory should handle missing optional fields
     */
    public function test2(): void
    {
        $result = UserLoginEntityFactory::build(
            $this->arrayData(),
            $this->mockPasswordHasher()
        );

        $this->assertEquals($this->arrayData()['first_name'], $result->getFirstName());
        $this->assertEquals($this->arrayData()['last_name'], $result->getLastName());
        $this->assertEquals(new Email($this->arrayData()['email']), $result->getEmail());
        $this->assertEquals($this->arrayData()['bio'], $result->getBio());
        $this->assertEquals($this->arrayData()['location'], $result->getLocation());
        $this->assertEquals($this->arrayData()['skills'], $result->getSkills());
        $this->assertEquals($this->arrayData()['profile_image'], $result->getProfileImage());
    }
}