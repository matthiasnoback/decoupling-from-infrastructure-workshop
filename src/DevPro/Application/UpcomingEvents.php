<?php
declare(strict_types=1);

namespace DevPro\Application;

interface UpcomingEvents
{
    public function add(UpcomingEvent $upcomingEvent);

    /**
     * @return array & UpcomingEvent[]
     */
    public function findAll(): array;
}
