<?php
declare(strict_types=1);

namespace Test\Adapter\DevPro\Infrastructure\Database;

use DevPro\Application\Users\CouldNotFindSecurityUser;
use DevPro\Application\Users\SecurityUsers;
use DevPro\Domain\Model\User\User;
use DevPro\Domain\Model\User\UserRepository;
use DevPro\Infrastructure\ContainerConfiguration;
use Test\Adapter\DevPro\Infrastructure\OutputAdapterTestServiceContainer;
use PHPUnit\Framework\TestCase;

final class SecurityUsersRepositoryUsingDbalTest extends TestCase
{
    private UserRepository $userRepository;
    private SecurityUsers $securityUsers;

    protected function setUp(): void
    {
        $container = new OutputAdapterTestServiceContainer(
            ContainerConfiguration::createForOutputAdapterTesting(getenv())
        );

        $this->userRepository = $container->userRepository();
        $this->securityUsers = $container->securityUsers();
    }

    /**
     * @test
     */
    public function if_a_user_has_been_created_it_becomes_a_security_user_too(): void
    {
        $user = User::createNormalUser($this->userRepository->nextIdentity(), 'Username');
        $this->userRepository->save($user);

        $securityUser = $this->securityUsers->getByUsername('Username');
        self::assertEquals($user->userId()->asString(), $securityUser->id());
    }

    /**
     * @test
     */
    public function it_fails_if_there_is_no_user_with_the_given_username(): void
    {
        $this->expectException(CouldNotFindSecurityUser::class);
        $this->securityUsers->getByUsername('Unknown');
    }
}
