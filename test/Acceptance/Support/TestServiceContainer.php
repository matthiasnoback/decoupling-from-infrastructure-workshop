<?php
declare(strict_types=1);

namespace Test\Acceptance\Support;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Domain\Model\Ticket\TicketRepository;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Domain\Model\User\UserRepository;

final class TestServiceContainer
{
    /**
     * @var ClockForTesting | null
     */
    private $clock;

    /**
     * @var EventDispatcher | null
     */
    private $eventDispatcher;

    /**
     * @var EventSubscriberSpy | null
     */
    private $eventSubscriberSpy;

    /**
     * @var InMemoryUserRepository | null
     */
    private $userRepository;

    /**
     * @var InMemoryTrainingRepository | null
     */
    private $trainingRepository;

    /**
     * @var InMemoryTicketRepository
     */
    private $ticketRepository;

    private function clock(): ClockForTesting
    {
        return $this->clock ?? $this->clock = new ClockForTesting();
    }

    public function eventDispatcher(): EventDispatcher
    {
        if ($this->eventDispatcher === null) {
            $this->eventDispatcher = new EventDispatcher();

            $this->eventDispatcher->subscribeToAllEvents($this->eventSubscriberSpy());

            // Register your own subscribers here:
            // $this->>eventDispatcher->registerSubscriber(EventClass::class, [$this->service(), 'methodName']);
        }

        return $this->eventDispatcher;
    }

    public function setCurrentDate(string $date): void
    {
        $this->clock()->setCurrentDate($date);
    }

    public function eventSubscriberSpy(): EventSubscriberSpy
    {
        return $this->eventSubscriberSpy ?? $this->eventSubscriberSpy = new EventSubscriberSpy();
    }

    /**
     * @return array<object>
     */
    public function dispatchedEvents(): array
    {
        return $this->eventSubscriberSpy()->dispatchedEvents();
    }

    public function userRepository(): UserRepository
    {
        return $this->userRepository ?? $this->userRepository = new InMemoryUserRepository($this->eventDispatcher());
    }

    public function trainingRepository(): TrainingRepository
    {
        return $this->trainingRepository ?? $this->trainingRepository = new InMemoryTrainingRepository($this->eventDispatcher());
    }

    public function ticketRepository(): TicketRepository
    {
        return $this->ticketRepository ?? $this->ticketRepository = new InMemoryTicketRepository($this->eventDispatcher());
    }
}
