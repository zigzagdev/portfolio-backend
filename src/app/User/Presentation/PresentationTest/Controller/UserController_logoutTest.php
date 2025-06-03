<?php

namespace App\User\Presentation\PresentationTest\Controller;

use App\User\Application\UseCase\LogoutUserUseCase;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Domain\Service\AuthServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\User\Presentation\Controller\UserController;
use Mockery;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Factory\UserEntityFactory;
use App\Common\Domain\UserId;
use App\Models\User;

class UserController_logoutTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
        $this->controller = new UserController();
    }

    protected function tearDown(): void
    {
        $this->refresh();
        parent::tearDown();
    }

    private function refresh(): void
    {
        if (env('APP_ENV') === 'testing') {
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');
            User::truncate();
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    private function mockUseCase(): LogoutUserUseCase
    {
        return new LogoutUserUseCase(
            $this->mockAuthService(),
            $this->mockRepository()
        );
    }

    private function mockAuthService(): AuthServiceInterface
    {
        $authService = Mockery::mock(AuthServiceInterface::class);

        $authService
            ->shouldReceive('attemptLogout')
            ->with(Mockery::type(UserEntity::class))
            ->andReturnNull();

        return $authService;
    }

    private function mockRepository(): UserRepositoryInterface
    {
        $repository = Mockery::mock(UserRepositoryInterface::class);

        $repository
            ->shouldReceive('findById')
            ->with(Mockery::type(UserId::class))
            ->andReturn($this->mockEntity());

        return $repository;
    }

    private function mockEntity(): UserEntity
    {
        $factory = Mockery::mock(
            'alias:' . UserEntityFactory::class
        );

        $entity = Mockery::mock(UserEntity::class);

        $factory
            ->shouldReceive('build')
            ->with($this->arrayData(), $this->mockPasswordHasher())
            ->andReturn($entity);

        $entity
            ->shouldReceive('getId')
            ->andReturn(new UserId(1));

        return $entity;
    }

    private function arrayData(): array
    {
        return [
            'first_name' => 'John',
            'last_name' => 'Terry',
            'email' => 'chelsea-26@test.com',
            'password' => 'test1234',
        ];
    }

    private function mockPasswordHasher(): PasswordHasherInterface
    {
        $hasher = Mockery::mock(PasswordHasherInterface::class);

        $hasher
            ->shouldReceive('hash')
            ->with($this->arrayData()['password'])
            ->andReturn($this->arrayData()['password']);

        return $hasher;
    }

    public function test1(): void
    {
        $user = User::create($this->arrayData());

        Auth::shouldReceive('user')
            ->andReturn($user);

        $useCase = $this->mockUseCase();

        $this->controller->logout($useCase);

        $this->assertTrue(true);
    }
}