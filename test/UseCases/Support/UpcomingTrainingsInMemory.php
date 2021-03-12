<?php
declare(strict_types=1);

namespace Test\UseCases\Support;

use DevPro\Application\Trainings\UpcomingTraining;
use DevPro\Application\Trainings\UpcomingTrainings;
use DevPro\Domain\Model\Training\TrainingWasScheduled;

final class UpcomingTrainingsInMemory implements UpcomingTrainings
{
    /**
     * @var array<UpcomingTraining>
     */
    private array $upcomingTrainings = [];

    public function whenTrainingWasScheduled(
        TrainingWasScheduled $event
    ): void {
        $this->upcomingTrainings[] = new UpcomingTraining($event->title());
    }

    public function findAll(): array
    {
        return $this->upcomingTrainings;
    }
}
