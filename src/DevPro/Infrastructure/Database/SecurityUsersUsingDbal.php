<?php
declare(strict_types=1);

namespace DevPro\Infrastructure\Database;

use Assert\Assert;
use DevPro\Application\Users\CouldNotFindSecurityUser;
use DevPro\Application\Users\SecurityUsers;
use DevPro\Application\Users\SecurityUser;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Result;

final class SecurityUsersUsingDbal implements SecurityUsers
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getByUsername(string $name): SecurityUser
    {
        $result = $this->connection->executeQuery(
            'SELECT * FROM users WHERE username = ?',
            [
                $name
            ]
        );
        Assert::that($result)->isInstanceOf(Result::class);

        $record = $result->fetchAssociative();
        if ($record === false) {
            throw new CouldNotFindSecurityUser(
                sprintf('Could not find security user with name "%s"', $name)
            );
        }

        return new SecurityUser($record['id'], $record['username']);
    }
}
