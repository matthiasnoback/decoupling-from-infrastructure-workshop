<?php
declare(strict_types=1);

namespace DevPro\Application\Training;

use DevPro\Domain\Model\Training\ScheduledDate;

final class UpcomingTraining
{
    private string $title;
    private ScheduledDate $scheduledDate;

    public function __construct(string $title, ScheduledDate $scheduledDate)
    {
        $this->title = $title;
        $this->scheduledDate = $scheduledDate;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function scheduledDate(): ScheduledDate
    {
        return $this->scheduledDate;
    }
}
