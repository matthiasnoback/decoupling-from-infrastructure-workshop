<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Meetups;

use MeetupOrganizing\Domain\Model\Common\Country;
use MeetupOrganizing\Domain\Model\Common\DateAndTime;
use MeetupOrganizing\Domain\Model\User\UserId;

final class ScheduleMeetup
{
    private string $organizerId;
    private string $countryCode;
    private string $title;
    private string $scheduledDate;

    public function __construct(string $organizerId, string $countryCode, string $title, string $scheduledDate)
    {
        $this->organizerId = $organizerId;
        $this->countryCode = $countryCode;
        $this->title = $title;
        $this->scheduledDate = $scheduledDate;
    }

    public function scheduledDate(): DateAndTime
    {
        return DateAndTime::fromString($this->scheduledDate);
    }

    public function organizerId(): UserId
    {
        return UserId::fromString($this->organizerId);
    }

    public function countryCode(): Country
    {
        return Country::fromString($this->countryCode);
    }

    public function title(): string
    {
        return $this->title;
    }
}
