<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Database;

use Assert\Assert;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Result;
use MeetupOrganizing\Application\Clock;
use MeetupOrganizing\Application\Meetups\UpcomingMeetup;
use MeetupOrganizing\Application\Meetups\UpcomingMeetupRepository;
use MeetupOrganizing\Domain\Model\Common\DateAndTime;

final class UpcomingMeetupRepositoryUsingDbal implements UpcomingMeetupRepository
{
    private Connection $connection;
    private Clock $clock;

    public function __construct(Connection $connection, Clock $clock)
    {
        $this->connection = $connection;
        $this->clock = $clock;
    }

    public function findAll(): array
    {
        $result = $this->connection->executeQuery(
            'SELECT * FROM meetups WHERE scheduledDate > ?',
            [
                $this->clock->currentTime()->format(DateAndTime::DATE_TIME_FORMAT)
            ]
        );
        Assert::that($result)->isInstanceOf(Result::class);

        $records = $result->fetchAllAssociative();

        return array_map(fn (array $record) => UpcomingMeetup::fromDatabaseRecord($record), $records);
    }
}
