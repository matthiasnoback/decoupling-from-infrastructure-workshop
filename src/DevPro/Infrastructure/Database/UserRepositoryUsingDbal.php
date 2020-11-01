<?php
declare(strict_types=1);

namespace DevPro\Infrastructure\Database;

use Assert\Assert;
use DevPro\Domain\Model\User\User;
use DevPro\Domain\Model\User\UserId;
use DevPro\Domain\Model\User\UserRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Result;
use Ramsey\Uuid\Uuid;
use RuntimeException;

final class UserRepositoryUsingDbal implements UserRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user): void
    {
        $this->connection->insert('users', $user->getDatabaseRecordData());
    }

    public function getById(UserId $userId): User
    {
        $result = $this->connection->executeQuery(
            'SELECT * FROM users WHERE id = ?',
            [
                $userId->asString()
            ]
        );
        Assert::that($result)->isInstanceOf(Result::class);

        $record = $result->fetchAssociative();
        if ($record === false) {
            throw new RuntimeException(
                sprintf('Could not find user with ID "%s"', $userId->asString())
            );
        }

        return User::fromDatabaseRecord($record);
    }

    public function nextIdentity(): UserId
    {
        return UserId::fromString(Uuid::uuid4()->toString());
    }
}
