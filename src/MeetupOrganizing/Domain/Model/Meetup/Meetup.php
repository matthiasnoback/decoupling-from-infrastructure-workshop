<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\Meetup;

use Assert\Assert;
use MeetupOrganizing\Domain\Model\Common\Country;
use MeetupOrganizing\Domain\Model\Common\DateAndTime;
use MeetupOrganizing\Domain\Model\Common\EventRecordingCapabilities;
use MeetupOrganizing\Domain\Model\Common\Mapping;
use MeetupOrganizing\Domain\Model\User\UserId;

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

    public static function fromDatabaseRecord(array $meetupRecord): self
    {
        $meetup = new self();

        $meetup->meetupId = MeetupId::fromString(self::getString($meetupRecord, 'meetupId'));
        $meetup->organizerId = UserId::fromString(self::getString($meetupRecord, 'organizerId'));
        $meetup->country = Country::fromString(self::getString($meetupRecord, 'country'));
        $meetup->title = self::getString($meetupRecord, 'title');
        $meetup->description = self::getString($meetupRecord, 'description');
        $meetup->scheduledDate = DateAndTime::fromString(self::getString($meetupRecord, 'scheduledDate'));

        return $meetup;
    }

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

    public function changeTitle(string $newTitle): void
    {
        $this->title = $newTitle;
    }
}
