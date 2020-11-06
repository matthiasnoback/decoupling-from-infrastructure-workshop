<?php
declare(strict_types=1);

namespace DevPro\Application;

interface ListUpcomingTrainings
{
    /**
     * @return array<UpcomingTraining>
     */
    public function listAll(): array;
}
