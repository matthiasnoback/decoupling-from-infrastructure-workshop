<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Synchronizer\SingleDatabaseSynchronizer;

final class SchemaManager
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function updateSchema(): void
    {
        $synchronizer = new SingleDatabaseSynchronizer($this->connection);
        $synchronizer->updateSchema($this->provideSchema(), true);
    }

    public function truncateTables(): void
    {
        foreach ($this->provideSchema()->getTables() as $table) {
            $this->connection->executeStatement(
                $this->connection->getDatabasePlatform()->getTruncateTableSQL($table->getName())
            );
        }
    }

    private function provideSchema(): Schema
    {
        $schema = new Schema();

        $users = $schema->createTable('users');
        $users->addColumn('id', 'string');
        $users->addColumn('username', 'string');
        $users->addColumn('isOrganizer', 'boolean');
        $users->setPrimaryKey(['id']);

        $meetups = $schema->createTable('meetups');
        $meetups->addColumn('id', 'string');
        $meetups->addColumn('organizerId', 'string');
        $meetups->addColumn('title', 'string');
        $meetups->addColumn('description', 'string');
        $meetups->addColumn('scheduledDate', 'string');
        $meetups->addColumn('country', 'string');
        $meetups->setPrimaryKey(['id']);

        // Here you can add your own tables to the schema

        return $schema;
    }
}
