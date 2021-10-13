<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Database;

use Assert\Assert;
use MeetupOrganizing\Domain\Model\Meetup\Meetup;
use MeetupOrganizing\Domain\Model\Meetup\MeetupId;
use MeetupOrganizing\Domain\Model\Meetup\MeetupRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Result;
use Ramsey\Uuid\Uuid;
use RuntimeException;

final class MeetupRepositoryUsingDbal implements MeetupRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(Meetup $meetup): void
    {
        $this->connection->insert('meetups', $meetup->getDatabaseRecordData());
    }

    public function getById(MeetupId $meetupId): Meetup
    {
        $result = $this->connection->executeQuery(
            'SELECT * FROM meetups WHERE id = ?',
            [
                $meetupId->asString()
            ]
        );
        Assert::that($result)->isInstanceOf(Result::class);

        $record = $result->fetchAssociative();
        if ($record === false) {
            throw new RuntimeException(
                sprintf('Could not find meetups with ID "%s"', $meetupId->asString())
            );
        }

        return Meetup::fromDatabaseRecord($record);
    }

    public function nextIdentity(): MeetupId
    {
        return MeetupId::fromString(Uuid::uuid4()->toString());
    }
}
