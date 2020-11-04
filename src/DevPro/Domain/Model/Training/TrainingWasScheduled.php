<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Training;

use DateTimeImmutable;
use DevPro\Domain\Model\Common\Country;
use DevPro\Domain\Model\User\UserId;

final class TrainingWasScheduled
{
    private TrainingId $trainingId;
    private UserId $organizerId;
    private Country $country;
    private string $title;
    private DateTimeImmutable $scheduledDate;

    public function __construct(
        TrainingId $trainingId,
        UserId $organizerId,
        Country $country,
        string $title,
        DateTimeImmutable $scheduledDate
    ) {
        $this->trainingId = $trainingId;
        $this->organizerId = $organizerId;
        $this->country = $country;
        $this->title = $title;
        $this->scheduledDate = $scheduledDate;
    }

    public function trainingId(): TrainingId
    {
        return $this->trainingId;
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

    public function scheduledDate(): DateTimeImmutable
    {
        return $this->scheduledDate;
    }
}
