<?php
declare(strict_types=1);

namespace DevPro\Application\ListUpcomingEvents;

interface ListUpcomingEvents
{
    /**
     * @return array & UpcomingEvent[]
     */
    public function listUpcomingEvents(): array;

    public function add(UpcomingEvent $upcomingEvent): void;
}
