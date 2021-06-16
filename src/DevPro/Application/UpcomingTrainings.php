<?php
declare(strict_types=1);

namespace DevPro\Application;

interface UpcomingTrainings
{
    /**
     * @return array<UpcomingTraining>
     */
    public function findAllUpcomingTrainings(): array;
}
