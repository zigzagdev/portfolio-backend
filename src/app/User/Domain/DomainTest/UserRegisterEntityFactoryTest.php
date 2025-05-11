<?php

namespace App\User\Domain\DomainTest;

use InvalidArgumentException;
use Tests\TestCase;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Factory\UserEntityFactory;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;

class UserRegisterEntityFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function mockInterface(): PasswordHasherInterface
    {
        $mockInterface = $this->createMock(PasswordHasherInterface::class);

        $mockInterface
            ->method('hash')
            ->with($this->testData()['password'])
            ->willReturn($this->testData()['password']);

        return $mockInterface;
    }

    private function testData(): array
    {
        return [
            'id' => null,
            'first_name' => 'Cristiano',
            'last_name' => 'Ronaldo',
            'email' => 'manchester-united@test.com',
            'password' => 'test1234',
            'bio' => 'I am a football player',
            'location' => 'Manchester',
            'skills' => ['Laravel', 'React'],
            'profile_image' => 'https://example.com/profile.jpg',
        ];
    }

    /**
     * @test
     * @testdox UserEntityFactory_build_successfully check type (id is null)
     */
    public function test1(): void
    {
        $result = UserEntityFactory::build($this->testData(), $this->mockInterface());

        $this->assertInstanceOf(UserEntity::class, $result);
    }

    /**
     * @test
     * @testdox UserEntityFactory_build_successfully check type (id is not null)
     */
    public function test2(): void
    {
        $data = $this->testData();
        $data['id'] = 1;

        $result = UserEntityFactory::build($data, $this->mockInterface());

        $this->assertInstanceOf(UserEntity::class, $result);
    }

    /**
     * @test
     * @testdox UserEntityFactory_build_failed (email is not string)
     */
    public function test3(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $data = $this->testData();
        $data['email'] = 1;

        $result = UserEntityFactory::build($data, $this->mockInterface());

        $this->assertNotInstanceOf(UserEntity::class, $result);
    }

    /**
     * @test
     * @testdox UserEntityFactory_build_failed (password is wrong)
     */
    public function test4(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $data = $this->testData();
        $data['password'] = 1;

        $result = UserEntityFactory::build($data, $this->mockInterface());

        $this->assertNotInstanceOf(UserEntity::class, $result);
    }
}