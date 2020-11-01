<?php
declare(strict_types=1);

namespace Test\Acceptance\Support;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Infrastructure\AbstractServiceContainer;

final class TestAbstractServiceContainer extends AbstractServiceContainer
{
    private ?ClockForTesting $clock;
    private ?EventSubscriberSpy $eventSubscriberSpy;

    public function setCurrentDate(string $date): void
    {
        $this->clock()->setCurrentDate($date);
    }

    /**
     * @return array<object>
     */
    public function dispatchedEvents(): array
    {
        return $this->eventSubscriberSpy()->dispatchedEvents();
    }

    protected function registerSubscribers(EventDispatcher $eventDispatcher): void
    {
        // Register subscribers that should be available in every environment in the parent method
        parent::registerSubscribers($eventDispatcher);

        // Register additional event subscribers that are only meant to be notified in a testing environment:

        $eventDispatcher->subscribeToAllEvents($this->eventSubscriberSpy());

        $eventDispatcher->subscribeToAllEvents(
            function (object $event): void {
                echo '- Event dispatched: ' . method_exists($event, '__toString')
                    ? (string)$event
                    : get_class($event) . "\n";
            }
        );
    }

    protected function clock(): ClockForTesting
    {
        return $this->clock ?? $this->clock = new ClockForTesting();
    }

    private function eventSubscriberSpy(): EventSubscriberSpy
    {
        return $this->eventSubscriberSpy ?? $this->eventSubscriberSpy = new EventSubscriberSpy();
    }
}
