<?php
declare(strict_types=1);

namespace Test\UseCases\Support;

use DevPro\Application\Training\UpcomingTraining;
use DevPro\Application\Training\UpcomingTrainings;
use DevPro\Domain\Model\Training\TrainingWasScheduled;

final class InMemoryUpcomingTrainings implements UpcomingTrainings
{
    /**
     * @var array<UpcomingTraining>
     */
    private array $trainings = [];

    public function findAll(): array
    {
        return $this->trainings;
    }

    public function whenTrainingWasScheduled(TrainingWasScheduled $event): void
    {
        $this->trainings[] = new UpcomingTraining($event->title());
    }
}
