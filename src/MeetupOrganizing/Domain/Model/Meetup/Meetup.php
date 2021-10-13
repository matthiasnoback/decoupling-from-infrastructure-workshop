<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\Meetup;

use Assert\Assert;
use MeetupOrganizing\Domain\Model\Common\Country;
use MeetupOrganizing\Domain\Model\Common\DateAndTime;
use MeetupOrganizing\Domain\Model\Common\EventRecordingCapabilities;
use MeetupOrganizing\Domain\Model\User\UserId;

final class Meetup
{
    use EventRecordingCapabilities;

    private MeetupId $meetupId;
    private UserId $organizerId;
    private string $title;
    private DateAndTime $scheduledDate;
    private Country $country;

    private function __construct()
    {
    }

    public static function schedule(
        MeetupId $meetupId,
        UserId $organizerId,
        Country $country,
        string $title,
        DateAndTime $scheduledDate
    ): self {
        Assert::that($title)->notEmpty('Title should not be empty');

        $meetup = new self();

        $meetup->meetupId = $meetupId;
        $meetup->organizerId = $organizerId;
        $meetup->country = $country;
        $meetup->title = $title;
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

    public static function fromDatabaseRecord(array $record): self
    {
        $meetup = new self();

        $meetup->meetupId = MeetupId::fromString($record['id']);
        $meetup->organizerId = UserId::fromString($record['organizerId']);
        $meetup->country = Country::fromString($record['country']);
        $meetup->title = $record['title'];
        $meetup->scheduledDate = DateAndTime::fromString($record['scheduledDate']);

        return $meetup;
    }

    public function getDatabaseRecordData(): array
    {
        return [
            'id' => $this->meetupId->asString(),
            'organizerId' => $this->organizerId->asString(),
            'country' => $this->country->asString(),
            'title' => $this->title,
            'scheduledDate' => $this->scheduledDate->asString()
        ];
    }
}
