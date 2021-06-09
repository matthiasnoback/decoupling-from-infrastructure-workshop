<?php
declare(strict_types=1);

namespace DevPro\Application;

use DevPro\Domain\Model\Common\Country;
use DevPro\Domain\Model\Training\ScheduledDate;
use DevPro\Domain\Model\User\UserId;

final class ScheduleTraining
{
    private string $organizerId;
    private string $country;
    private string $title;
    private string $scheduledDate;

    public function __construct(string $organizerId, string $country, string $title, string $scheduledDate)
    {
        $this->organizerId = $organizerId;
        $this->country = $country;
        $this->title = $title;
        $this->scheduledDate = $scheduledDate;
    }

    public function organizerId(): UserId
    {
        return UserId::fromString($this->organizerId);
    }

    public function country(): Country
    {
        return Country::fromString($this->country);
    }

    public function title(): string
    {
        return $this->title;
    }

    public function scheduledDate(): ScheduledDate
    {
        return ScheduledDate::fromString($this->scheduledDate);
    }
}
