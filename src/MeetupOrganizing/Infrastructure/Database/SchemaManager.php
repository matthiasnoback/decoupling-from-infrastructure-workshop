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

        $this->addUsersTable($schema);

        $this->addMeetupsTable($schema);

        $this->addRsvpsTable($schema);

        // Here you can add your own tables to the schema

        return $schema;
    }

    private function addUsersTable(Schema $schema): void
    {
        $users = $schema->createTable('users');
        $users->addColumn('userId', 'string');
        $users->addColumn('username', 'string');
        $users->addColumn('isOrganizer', 'boolean');
        $users->setPrimaryKey(['userId']);
    }

    private function addMeetupsTable(Schema $schema): void
    {
        $meetups = $schema->createTable('meetups');
        $meetups->addColumn('meetupId', 'string');
        $meetups->addColumn('organizerId', 'string');
        $meetups->addColumn('title', 'string');
        $meetups->addColumn('description', 'string');
        $meetups->addColumn('scheduledDate', 'string');
        $meetups->addColumn('country', 'string');
        $meetups->setPrimaryKey(['meetupId']);
    }

    private function addRsvpsTable(Schema $schema): void
    {
        $rsvps = $schema->createTable('rsvps');
        $rsvps->addColumn('attendeeId', 'string');
        $rsvps->addColumn('meetupId', 'string');
        $rsvps->setPrimaryKey(['attendeeId', 'meetupId']);
    }
}
