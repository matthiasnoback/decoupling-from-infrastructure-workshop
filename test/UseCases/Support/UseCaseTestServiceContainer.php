<?php
declare(strict_types=1);

namespace Test\UseCases\Support;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Infrastructure\AbstractServiceContainer;
use DevPro\Infrastructure\ContainerConfiguration;

final class UseCaseTestServiceContainer extends AbstractServiceContainer
{
    private ?ClockForTesting $clock = null;
    private ?EventSubscriberSpy $eventSubscriberSpy = null;

    public static function create(): self
    {
        return new self(ContainerConfiguration::createForUseCaseTesting());
    }

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

        $eventDispatcher->subscribeToAllEvents($this->eventPrinter());
    }

    protected function clock(): ClockForTesting
    {
        return $this->clock ?? $this->clock = new ClockForTesting();
    }

    private function eventSubscriberSpy(): EventSubscriberSpy
    {
        return $this->eventSubscriberSpy ?? $this->eventSubscriberSpy = new EventSubscriberSpy();
    }

    private function eventPrinter(): callable
    {
        return function (object $event): void {
            $eventAsString = method_exists($event, '__toString')
                ? (string)$event
                : get_class($event);

            echo '- Event dispatched: ' . $eventAsString . "\n";
        };
    }
}
