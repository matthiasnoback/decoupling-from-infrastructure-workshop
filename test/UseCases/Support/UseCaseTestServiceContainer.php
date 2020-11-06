<?php
declare(strict_types=1);

namespace Test\UseCases\Support;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Domain\Model\Training\TrainingWasScheduled;
use DevPro\Infrastructure\AbstractServiceContainer;
use DevPro\Infrastructure\ContainerConfiguration;

final class UseCaseTestServiceContainer extends AbstractServiceContainer
{
    private ?ClockForTesting $clock = null;
    private ?EventSubscriberSpy $eventSubscriberSpy = null;

    private ?InMemoryUserRepository $userRepository = null;
    private ?InMemoryTrainingRepository $trainingRepository = null;
    private ?InMemoryTicketRepository $ticketRepository = null;
    private ?InMemoryListUpcomingTrainings $listUpcomingTrainings = null;
    private ?FakeNationalHolidays $nationalHolidays = null;

    public static function create(): self
    {
        return new self(ContainerConfiguration::createForUseCaseTesting());
    }

    public function setCurrentDate(string $date): void
    {
        $this->clock()->setCurrentDate($date);
    }

    public function boot(): void
    {
        parent::boot(); // TODO: Change the autogenerated stub
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
                echo '- Event dispatched: ' . (method_exists($event, '__toString')
                    ? (string)$event
                    : get_class($event) . "\n");
            }
        );

        $eventDispatcher->registerSubscriber(
            TrainingWasScheduled::class,
            [$this->listUpcomingTrainings(), 'whenTrainingWasScheduled']
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

    public function userRepository(): InMemoryUserRepository
    {
        return $this->userRepository ?? $this->userRepository = new InMemoryUserRepository();
    }

    public function trainingRepository(): InMemoryTrainingRepository
    {
        return $this->trainingRepository ?? $this->trainingRepository = new InMemoryTrainingRepository();
    }

    public function ticketRepository(): InMemoryTicketRepository
    {
        return $this->ticketRepository ?? $this->ticketRepository = new InMemoryTicketRepository();
    }

    public function listUpcomingTrainings(): InMemoryListUpcomingTrainings
    {
        return $this->listUpcomingTrainings ?? $this->listUpcomingTrainings = new InMemoryListUpcomingTrainings();
    }

    public function nationalHolidays(): FakeNationalHolidays
    {
        return $this->nationalHolidays ?? $this->nationalHolidays = new FakeNationalHolidays();
    }
}