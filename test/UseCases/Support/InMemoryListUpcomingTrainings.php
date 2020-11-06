<?php
declare(strict_types=1);

namespace Test\UseCases\Support;

use DevPro\Application\ListUpcomingTrainings;
use DevPro\Application\UpcomingTraining;
use DevPro\Domain\Model\Training\TrainingWasScheduled;

final class InMemoryListUpcomingTrainings implements ListUpcomingTrainings
{
    /**
     * @var array<UpcomingTraining>
     */
    private array $upcomingTrainings = [];

    public function whenTrainingWasScheduled(TrainingWasScheduled $event): void
    {
        $this->upcomingTrainings[] = new UpcomingTraining($event->title());
    }

    public function listAll(): array
    {
        return $this->upcomingTrainings;
    }
}
