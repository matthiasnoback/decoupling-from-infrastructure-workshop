<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Database;

use Assert\Assert;
use MeetupOrganizing\Application\Users\CouldNotFindUser;
use MeetupOrganizing\Application\Users\Users;
use MeetupOrganizing\Application\Users\User;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Result;
use MeetupOrganizing\Domain\Model\User\UserId;

final class UsersUsingDbal implements Users
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getByUsername(string $username): User
    {
        $result = $this->connection->executeQuery(
            'SELECT * FROM users WHERE username = ?',
            [
                $username
            ]
        );
        Assert::that($result)->isInstanceOf(Result::class);

        $record = $result->fetchAssociative();
        if ($record === false) {
            throw CouldNotFindUser::withUsername($username);
        }

        return User::fromDatabaseRecord($record);
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
}
