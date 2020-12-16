<?php
declare(strict_types=1);

namespace DevPro\Application\Training;

interface UpcomingTrainings
{
    /**
     * @return array<UpcomingTraining> & UpcomingTraining[]
     */
    public function findAll(): array;
}
