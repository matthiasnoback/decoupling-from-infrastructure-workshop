<?php
declare(strict_types=1);

namespace Test\Adapter\DevPro\Infrastructure\Database;

use DevPro\Domain\Model\User\User;
use DevPro\Domain\Model\User\UserRepository;
use DevPro\Infrastructure\ContainerConfiguration;
use Test\Adapter\DevPro\Infrastructure\OutputAdapterTestServiceContainer;
use PHPUnit\Framework\TestCase;

final class UserRepositoryUsingDbalTest extends TestCase
{
    private UserRepository $repository;

    protected function setUp(): void
    {
        $container = new OutputAdapterTestServiceContainer(ContainerConfiguration::createForOutputAdapterTesting());

        $this->repository = $container->userRepository();
    }

    /**
     * @test
     */
    public function it_can_save_and_retrieve_a_normal_user(): void
    {
        $user = User::createNormalUser($this->repository->nextIdentity(), 'Username');

        $this->repository->save($user);

        $fromDatabase = $this->repository->getById($user->userId());

        self::assertEquals($user, $fromDatabase);
    }

    /**
     * @test
     */
    public function it_can_save_and_retrieve_an_organizer(): void
    {
        $organizer = User::createOrganizer($this->repository->nextIdentity());

        $this->repository->save($organizer);

        $fromDatabase = $this->repository->getById($organizer->userId());

        self::assertEquals($organizer, $fromDatabase);
    }
}
