<?php
declare(strict_types=1);

namespace Test\Acceptance\Support;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Domain\Model\User\User;
use DevPro\Domain\Model\User\UserId;
use DevPro\Domain\Model\User\UserRepository;
use RuntimeException;

final class InMemoryUserRepository implements UserRepository
{
    /**
     * @var array & User[]
     */
    private $entities = [];

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function save(User $entity): void
    {
        $this->entities[$entity->userId()->asString()] = $entity;

        $this->eventDispatcher->dispatchAll($entity->releaseEvents());
    }

    public function getById(UserId $id): User
    {
        if (!isset($this->entities[$id->asString()])) {
            throw new RuntimeException('Could not find User with ID ' . $id->asString());
        }

        return $this->entities[$id->asString()];
    }
}
