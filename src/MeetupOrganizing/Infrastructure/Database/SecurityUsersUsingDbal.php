<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Database;

use Assert\Assert;
use MeetupOrganizing\Application\Users\CouldNotFindSecurityUser;
use MeetupOrganizing\Application\Users\SecurityUsers;
use MeetupOrganizing\Application\Users\SecurityUser;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Result;

final class SecurityUsersUsingDbal implements SecurityUsers
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getByUsername(string $username): SecurityUser
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
            throw CouldNotFindSecurityUser::withUsername($username);
        }

        return new SecurityUser($record['userId'], $record['username']);
    }
}
