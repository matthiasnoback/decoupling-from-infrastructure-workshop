<?php
declare(strict_types=1);

namespace Test\UseCases\Support;

use DevPro\Application\Clock;
use DevPro\Application\Training\UpcomingTraining;
use DevPro\Application\Training\UpcomingTrainings;
use DevPro\Domain\Model\Training\TrainingWasScheduled;

final class InMemoryUpcomingTrainings implements UpcomingTrainings
{
    /**
     * @var array<UpcomingTraining>
     */
    private array $trainings = [];
    private Clock $clock;

    public function __construct(Clock $clock)
    {
        $this->clock = $clock;
    }

    public function findAll(): array
    {
        return array_filter(
            $this->trainings,
            fn(UpcomingTraining $upcomingTraining) => $upcomingTraining->scheduledDate()->isInTheFuture(
                $this->clock->currentTime()
            )
        );
    }

    public function whenTrainingWasScheduled(TrainingWasScheduled $event): void
    {
        $this->trainings[] = new UpcomingTraining($event->title(), $event->scheduledDate());
    }
}
