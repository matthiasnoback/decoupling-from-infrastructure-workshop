<?php
declare(strict_types=1);

namespace Test\UseCases\Support;

final class EventSubscriberSpy
{
    /**
     * @var array<object> & object[]
     */
    private array $events = [];

    public function __invoke(object $event): void
    {
        $this->events[] = $event;
    }

    public function dispatchedEvents(): array
    {
        return $this->events;
    }
}
