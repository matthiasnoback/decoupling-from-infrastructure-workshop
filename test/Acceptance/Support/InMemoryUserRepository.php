<?php
declare(strict_types=1);

namespace Test\Acceptance\Support;

use DevPro\Domain\Model\User\User;
use DevPro\Domain\Model\User\UserId;
use DevPro\Domain\Model\User\UserRepository;
use Ramsey\Uuid\Uuid;
use RuntimeException;

final class InMemoryUserRepository implements UserRepository
{
    /**
     * @var array & User[]
     */
    private $entities = [];

    public function save(User $entity): void
    {
        $this->entities[$entity->userId()->asString()] = $entity;
    }

    public function getById(UserId $id): User
    {
        if (!isset($this->entities[$id->asString()])) {
            throw new RuntimeException('Could not find User with ID ' . $id->asString());
        }

        return $this->entities[$id->asString()];
    }

    public function nextIdentity(): UserId
    {
        return UserId::fromString(Uuid::uuid4()->toString());
    }
}
