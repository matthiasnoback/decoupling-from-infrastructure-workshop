<?php
declare(strict_types=1);

namespace Test\Acceptance\Support;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Application\BuyTicket;
use DevPro\Application\CreateUser;
use DevPro\Application\RegisterAttendee;
use DevPro\Application\TrainingEventSubscriber;
use DevPro\Application\UpcomingEventsRepository;
use DevPro\Domain\Model\Ticket\TicketRepository;
use DevPro\Domain\Model\Ticket\TicketWasBoughtForTraining;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Domain\Model\Training\TrainingWasScheduled;
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

    /**
     * @var TrainingEventSubscriber
     */
    private $trainingEventSubscriber;

    /**
     * @var UpcomingEventsRepository
     */
    private $upcomingEventsRepository;

    public function clock(): ClockForTesting
    {
        return $this->clock ?? $this->clock = new ClockForTesting();
    }

    public function eventDispatcher(): EventDispatcher
    {
        if ($this->eventDispatcher === null) {
            $this->eventDispatcher = new EventDispatcher();

            $this->eventDispatcher->subscribeToAllEvents($this->eventSubscriberSpy());

            $this->eventDispatcher->subscribeToAllEvents(
                function (object $event): void {
                    echo '- Event dispatched: ' . get_class($event) . "\n";
                }
            );

            // Register your own subscribers here:
            $this->eventDispatcher->registerSubscriber(
                TrainingWasScheduled::class,
                [$this->trainingEventSubscriber(), 'whenTrainingWasScheduled']
            );

            $this->eventDispatcher->registerSubscriber(
                TicketWasBoughtForTraining::class,
                [$this->registerAttendee(), 'whenTicketWasBoughtForTraining']
            );
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
        return $this->userRepository ?? $this->userRepository = new InMemoryUserRepository();
    }

    public function trainingRepository(): TrainingRepository
    {
        return $this->trainingRepository ?? $this->trainingRepository = new InMemoryTrainingRepository();
    }


    public function upcomingEventsRepository(): UpcomingEventsRepository
    {
        return $this->upcomingEventsRepository ?? $this->upcomingEventsRepository = new InMemoryUpcomingEventsRepository();
    }

    public function ticketRepository(): TicketRepository
    {
        return $this->ticketRepository ?? $this->ticketRepository = new InMemoryTicketRepository();
    }

    public function trainingEventSubscriber(): TrainingEventSubscriber
    {
        return $this->trainingEventSubscriber ?? $this->trainingEventSubscriber = new TrainingEventSubscriber($this->upcomingEventsRepository());
    }

    public function createUser(): CreateUser
    {
        return new CreateUser($this->userRepository(), $this->eventDispatcher());
    }

    public function buyTicket(): BuyTicket
    {
        return new BuyTicket(
            $this->userRepository(),
            $this->trainingRepository(),
            $this->ticketRepository(),
            $this->eventDispatcher()
        );
    }

    private function registerAttendee(): RegisterAttendee
    {
        return new RegisterAttendee(
            $this->trainingRepository(),
            $this->eventDispatcher()
        );
    }
}
