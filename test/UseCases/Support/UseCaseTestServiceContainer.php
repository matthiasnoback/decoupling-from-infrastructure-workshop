<?php
declare(strict_types=1);

namespace Test\UseCases\Support;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Domain\Model\Common\Date;
use DevPro\Infrastructure\AbstractServiceContainer;
use DevPro\Infrastructure\ContainerConfiguration;

final class UseCaseTestServiceContainer extends AbstractServiceContainer
{
    private ?ClockForTesting $clock = null;

    public static function create(): self
    {
        return new self(ContainerConfiguration::createForUseCaseTesting());
    }

    public function boot(): void
    {
        parent::boot();

        $this->schemaManager()->truncateTables();
    }

    /**
     * @param string $date Use the format YYYY-MM-DD
     */
    public function setCurrentDate(string $date): void
    {
        $this->clock()->setCurrentDate(Date::fromString($date));
    }

    protected function registerSubscribers(EventDispatcher $eventDispatcher): void
    {
        // Register subscribers that should be available in every environment in the parent method
        parent::registerSubscribers($eventDispatcher);

        // Register additional event subscribers that are only meant to be notified in a testing environment:

        $eventDispatcher->subscribeToAllEvents($this->eventPrinter());
    }

    protected function clock(): ClockForTesting
    {
        return $this->clock ?? $this->clock = new ClockForTesting();
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
