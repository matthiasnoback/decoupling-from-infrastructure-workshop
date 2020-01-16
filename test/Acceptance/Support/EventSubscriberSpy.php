<?php
declare(strict_types=1);

namespace Test\Acceptance\Support;

final class EventSubscriberSpy
{
    /**
     * @var array<object>
     */
    private $events = [];

    public function __invoke(object $event): void
    {
        $this->events[] = $event;
    }

    public function dispatchedEvents(): array
    {
        return $this->events;
    }
}
