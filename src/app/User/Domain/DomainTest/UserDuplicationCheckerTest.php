<?php

namespace App\User\Domain\DomainTest;

use Tests\TestCase;
use App\User\Domain\Service\UserDuplicationChecker;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;

class UserDuplicationCheckerTest extends TestCase
{
    private UserRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->getMockBuilder(UserRepositoryInterface::class)
            ->onlyMethods(['existsByEmail', 'save', 'findById'])
            ->getMock();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     * @testdox exists() returns true when email already exists
     */
    public function test1(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('existsByEmail')
            ->willReturn(true);

        $checker = new UserDuplicationChecker($this->repository);
        $email = new Email('duplicate@example.com');

        $this->assertTrue($checker->existsCheck($email));
    }

    /**
     * @test
     * @testdox exists() returns false when email does not exist
     */
    public function test2(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('existsByEmail')
            ->willReturn(false);

        $checker = new UserDuplicationChecker($this->repository);
        $email = new Email('new@example.com');

        $this->assertFalse($checker->existsCheck($email));
    }
}