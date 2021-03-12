<?php
declare(strict_types=1);

namespace DevPro\Application\Trainings;

interface UpcomingTrainings
{
    /**
     * @return array<UpcomingTraining>
     */
    public function findAll(): array;
}
