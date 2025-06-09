<?php

namespace App\User\Application\ApplicationTest;

use App\User\Application\Dto\LoginUserDto;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Factory\UserEntityFactory;
use App\User\Domain\ValueObject\AuthToken;
use App\User\Domain\ValueObject\Email;
use App\Common\Domain\ValueObject\UserId;
use Mockery;
use Tests\TestCase;

class LoginUserDtoTest extends TestCase
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
            'alias:'. UserEntityFactory::class
        );

        $entity = Mockery::mock(UserEntity::class);

        $factory
            ->shouldReceive('build')
            ->andReturn($entity);

        $entity
            ->shouldReceive('getUserId')
            ->andReturn(new UserId($this->arrayRequestData()['id']));

        $entity
            ->shouldReceive('getEmail')
            ->andReturn(new Email($this->arrayRequestData()['email']));

        $entity
            ->shouldReceive('getPassword')
            ->andReturn($this->arrayRequestData()['password']);

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
            'password' => 'test1234',
            'location' => null,
            'skills' => [],
            'profile_image' => 'https://example.com/profile.jpg',
        ];
    }

    /**
     * @test
     * @testdox LoginUserDto can be created from UserEntity and AuthToken
     */
    public function test1(): void
    {
        $user = $this->mockEntity();
        $token = new AuthToken('mocked-jwt-token');
        $dto = LoginUserDto::fromEntity($user, $token);


        $this->assertInstanceOf(LoginUserDto::class, $dto);
    }

    /**
     * @test
     * @testdox LoginUserDto check the value
     */
    public function test2(): void
    {
        $user = $this->mockEntity();
        $token = new AuthToken('mocked-jwt-token');
        $dto = LoginUserDto::fromEntity($user, $token);

        $this->assertEquals(new UserId($this->arrayRequestData()['id']), $dto->getUserId());
        $this->assertEquals(new Email($this->arrayRequestData()['email']), $dto->getEmail());
        $this->assertEquals('mocked-jwt-token', $dto->token->getValue());
    }
}