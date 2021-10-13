<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\Meetup;

use DateTimeImmutable;
use MeetupOrganizing\Domain\Model\Common\Country;
use MeetupOrganizing\Domain\Model\Common\DateAndTime;
use MeetupOrganizing\Domain\Model\User\UserId;

final class MeetupWasScheduled
{
    private MeetupId $meetupId;
    private UserId $organizerId;
    private Country $country;
    private string $title;
    private DateAndTime $scheduledDate;

    public function __construct(
        MeetupId $meetupId,
        UserId $organizerId,
        Country $country,
        string $title,
        DateAndTime $scheduledDate
    ) {
        $this->meetupId = $meetupId;
        $this->organizerId = $organizerId;
        $this->country = $country;
        $this->title = $title;
        $this->scheduledDate = $scheduledDate;
    }

    public function meetupId(): MeetupId
    {
        return $this->meetupId;
    }

    public function organizerId(): UserId
    {
        return $this->organizerId;
    }

    public function country(): Country
    {
        return $this->country;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function scheduledDate(): DateAndTime
    {
        return $this->scheduledDate;
    }
}
