<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Database;

use Assert\Assert;
use MeetupOrganizing\Domain\Model\User\CouldNotFindUser;
use MeetupOrganizing\Domain\Model\User\User;
use MeetupOrganizing\Domain\Model\User\UserId;
use MeetupOrganizing\Domain\Model\User\UserRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Result;
use Ramsey\Uuid\Uuid;

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
            'SELECT * FROM users WHERE userId = ?',
            [
                $userId->asString()
            ]
        );
        Assert::that($result)->isInstanceOf(Result::class);

        $record = $result->fetchAssociative();
        if ($record === false) {
            throw CouldNotFindUser::withId($userId);
        }

        return User::fromDatabaseRecord($record);
    }

    public function nextIdentity(): UserId
    {
        return UserId::fromString(Uuid::uuid4()->toString());
    }
}
