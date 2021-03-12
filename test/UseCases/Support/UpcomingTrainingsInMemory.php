<?php
declare(strict_types=1);

namespace Test\UseCases\Support;

use DevPro\Application\Trainings\UpcomingTrainings;

final class UpcomingTrainingsInMemory implements UpcomingTrainings
{
    public function findAll(): array
    {
        return [];
    }
}
