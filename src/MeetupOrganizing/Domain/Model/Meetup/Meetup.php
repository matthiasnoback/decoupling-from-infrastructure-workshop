<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\Meetup;

use Assert\Assert;
use MeetupOrganizing\Domain\Model\Common\Country;
use MeetupOrganizing\Domain\Model\Common\DateAndTime;
use MeetupOrganizing\Domain\Model\Common\EventRecordingCapabilities;
use MeetupOrganizing\Domain\Model\Common\Mapping;
use MeetupOrganizing\Domain\Model\User\UserId;
use MeetupOrganizing\Infrastructure\Database\SchemaManager;

final class Meetup
{
    use Mapping;
    use EventRecordingCapabilities;

    private MeetupId $meetupId;
    private UserId $organizerId;
    private string $title;
    private string $description;
    private DateAndTime $scheduledDate;
    private Country $country;

    /**
     * @var array<UserId>
     */
    private array $rsvps = [];

    private function __construct()
    {
    }

    public static function schedule(
        MeetupId $meetupId,
        UserId $organizerId,
        Country $country,
        string $title,
        string $description,
        DateAndTime $scheduledDate
    ): self {
        Assert::that($title)->notEmpty('Title should not be empty');
        Assert::that($description)->notEmpty('Description should not be empty');

        $meetup = new self();

        $meetup->meetupId = $meetupId;
        $meetup->organizerId = $organizerId;
        $meetup->country = $country;
        $meetup->title = $title;
        $meetup->description = $description;
        $meetup->scheduledDate = $scheduledDate;

        $meetup->recordThat(
            new MeetupWasScheduled($meetupId, $organizerId, $country, $title, $scheduledDate)
        );

        return $meetup;
    }

    public function meetupId(): MeetupId
    {
        return $this->meetupId;
    }

    /**
     * @param array<string,string|null> $meetupRecord
     * @param array<array<string,string|null>> $rsvpRecords
     *
     * @see SchemaManager::addMeetupsTable()
     * @see SchemaManager::addRsvpsTable()
     */
    public static function fromDatabaseRecord(array $meetupRecord, array $rsvpRecords): self
    {
        $meetup = new self();

        $meetup->meetupId = MeetupId::fromString(self::getString($meetupRecord, 'meetupId'));
        $meetup->organizerId = UserId::fromString(self::getString($meetupRecord, 'organizerId'));
        $meetup->country = Country::fromString(self::getString($meetupRecord, 'country'));
        $meetup->title = self::getString($meetupRecord, 'title');
        $meetup->description = self::getString($meetupRecord, 'description');
        $meetup->scheduledDate = DateAndTime::fromString(self::getString($meetupRecord, 'scheduledDate'));

        // @TODO convert records from the `rsvps` table to `UserId` objects:
        $meetup->rsvps = [];

        return $meetup;
    }

    /**
     * @see SchemaManager::addMeetupsTable()
     */
    public function getDatabaseRecordData(): array
    {
        return [
            'meetupId' => $this->meetupId->asString(),
            'organizerId' => $this->organizerId->asString(),
            'country' => $this->country->asString(),
            'title' => $this->title,
            'description' => $this->description,
            'scheduledDate' => $this->scheduledDate->asString()
        ];
    }

    /**
     * @return array<array<string,string>>
     *
     * @see SchemaManager::addRsvpsTable()
     */
    public function getRsvpRecordsData(): array
    {
        // @TODO implement
        return [];
    }

    public function changeTitle(string $newTitle): void
    {
        $this->title = $newTitle;
    }

    public function withRsvp(UserId $attendeeId): self
    {
        $self = clone $this;
        $self->rsvps[] = $attendeeId;

        return $self;
    }
}
