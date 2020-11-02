<?php
declare(strict_types=1);

namespace DevPro\Infrastructure\Database;

use Assert\Assert;
use DevPro\Application\Users\GetSecurityUser;
use DevPro\Application\Users\SecurityUser;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Result;
use RuntimeException;

final class GetSecurityUserUsingDbal implements GetSecurityUser
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function byUsername(string $name): SecurityUser
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
            throw new RuntimeException(
                sprintf('Could not find user with name "%s"', $name)
            );
        }

        return new SecurityUser($record['id'], $record['username']);
    }
}
