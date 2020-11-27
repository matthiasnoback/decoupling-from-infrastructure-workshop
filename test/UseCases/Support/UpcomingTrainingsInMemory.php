<?php
declare(strict_types=1);

namespace Test\UseCases\Support;

use DevPro\Application\UpcomingTrainings\UpcomingTraining;
use DevPro\Application\UpcomingTrainings\UpcomingTrainings;

final class UpcomingTrainingsInMemory implements UpcomingTrainings
{
    /**
     * @var array<UpcomingTraining>
     */
    private array $upcomingTrainings;

    public function findAll(): array
    {
        return $this->upcomingTrainings;
    }

    public function add(UpcomingTraining $upcomingTraining): void
    {
        $this->upcomingTrainings[] = $upcomingTraining;
    }
}
