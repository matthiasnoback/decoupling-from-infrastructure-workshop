<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Database;

use Assert\Assert;
use MeetupOrganizing\Application\Meetups\MeetupDetails;
use MeetupOrganizing\Application\Meetups\MeetupDetailsRepository;
use MeetupOrganizing\Domain\Model\Meetup\MeetupId;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Result;
use RuntimeException;

final class MeetupDetailsRepositoryUsingDbal implements MeetupDetailsRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getMeetupDetails(MeetupId $meetupId): MeetupDetails
    {
        $meetupResult = $this->connection->executeQuery(
            'SELECT * FROM meetups WHERE meetupId = ?',
            [
                $meetupId->asString()
            ]
        );
        Assert::that($meetupResult)->isInstanceOf(Result::class);

        $meetupRecord = $meetupResult->fetchAssociative();
        if ($meetupRecord === false) {
            throw new RuntimeException(
                sprintf('Could not find meetups with ID "%s"', $meetupId->asString())
            );
        }

        $rsvpResults = $this->connection->executeQuery(
            'SELECT users.username AS attendeeName FROM rsvps INNER JOIN users ON attendeeId = userId WHERE meetupId = ?',
            [
                $meetupId->asString()
            ]
        );
        Assert::that($rsvpResults)->isInstanceOf(Result::class);

        $rsvpRecords = $rsvpResults->fetchAllAssociative();

        return MeetupDetails::fromDatabaseRecord($meetupRecord, $rsvpRecords);
    }
}
