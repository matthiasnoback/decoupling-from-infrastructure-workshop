<?php
declare(strict_types=1);

namespace Test\Acceptance\Support;

use DevPro\Application\ListUpcomingEvents\ListUpcomingEvents;
use DevPro\Application\ListUpcomingEvents\UpcomingEvent;

final class InMemoryListUpcomingEvents implements ListUpcomingEvents
{
    /**
     * @var array & UpcomingEvent[]
     */
    private $upcomingEvents = [];

    /**
     * @inheritDoc
     */
    public function listUpcomingEvents(): array
    {
        return $this->upcomingEvents;
    }

    public function add(UpcomingEvent $upcomingEvent): void
    {
        $this->upcomingEvents[] = $upcomingEvent;
    }
}
