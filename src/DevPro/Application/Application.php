<?php
declare(strict_types=1);

namespace DevPro\Application;

use DevPro\Application\Users\CreateOrganizer;
use DevPro\Application\Users\CreateUser;
use DevPro\Domain\Model\User\UserId;
use DevPro\Infrastructure\AbstractServiceContainer;

final class Application implements ApplicationInterface
{
    private AbstractServiceContainer $container;

    public function __construct(AbstractServiceContainer $container)
    {
        $this->container = $container;
    }

    public function createUser(string $username): UserId
    {
        return $this->container->createUser()->handle(new CreateUser($username));
    }

    public function createOrganizer(): UserId
    {
        return $this->container->createOrganizer()->handle(new CreateOrganizer());
    }
}
