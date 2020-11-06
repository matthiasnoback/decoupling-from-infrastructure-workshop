<?php
declare(strict_types=1);

namespace DevPro\Application;

use Assert\Assert;
use DateTimeImmutable;
use DevPro\Domain\Model\Common\Country;
use DevPro\Domain\Model\User\UserId;

final class ScheduleTraining
{
    private string $organizerId;
    private string $title;
    private string $scheduledDate;
    private string $country;

    public function __construct(string $organizerId, string $title, string $scheduledDate, string $country)
    {
        $this->organizerId = $organizerId;
        $this->title = $title;
        $this->scheduledDate = $scheduledDate;
        $this->country = $country;
    }

    public function organizerId(): UserId
    {
        return UserId::fromString($this->organizerId);
    }

    public function title(): string
    {
        return $this->title;
    }

    public function scheduledDate(): DateTimeImmutable
    {
        $date = DateTimeImmutable::createFromFormat('d-m-Y', $this->scheduledDate);
        Assert::that($date)->isInstanceOf(DateTimeImmutable::class);

        return $date;
    }

    public function country(): Country
    {
        return Country::fromString($this->country);
    }
}
