<?php
declare(strict_types=1);

namespace DevPro\Application\UpcomingTrainings;

interface UpcomingTrainings
{
    /**
     * @return array<UpcomingTraining> & UpcomingTraining[]
     */
    public function findAll(): array;
}
