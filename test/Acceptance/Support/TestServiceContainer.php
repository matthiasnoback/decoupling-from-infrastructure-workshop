<?php
declare(strict_types=1);

namespace Test\Acceptance\Support;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Application\BuyTicket\BuyTicket;
use DevPro\Application\BuyTicket\RegisterAttendee;
use DevPro\Application\ListUpcomingEvents\ListUpcomingEvents;
use DevPro\Application\ListUpcomingEvents\UpcomingEvent;
use DevPro\Application\ScheduleTraining\ScheduleTraining;
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
     * @var InMemoryListUpcomingEvents | null
     */
    private $listUpcomingEvents;

    private function clock(): ClockForTesting
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
                    echo '- Event dispatched: ' . get_class($event) . ': ' . (string)$event . "\n";
                });

            $this->eventDispatcher->registerSubscriber(
                TrainingWasScheduled::class,
                function (TrainingWasScheduled $trainingWasScheduled): void {
                    $upcomingEvent = new UpcomingEvent(
                        $trainingWasScheduled->trainingId(),
                        $trainingWasScheduled->title()
                    );
                    $this->listUpcomingEvents()->add($upcomingEvent);
                }
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

    public function ticketRepository(): TicketRepository
    {
        return $this->ticketRepository ?? $this->ticketRepository = new InMemoryTicketRepository();
    }

    public function listUpcomingEvents(): ListUpcomingEvents
    {
        return $this->listUpcomingEvents ?? $this->listUpcomingEvents = new InMemoryListUpcomingEvents();
    }

    public function scheduleTraining(): ScheduleTraining
    {
        return new ScheduleTraining($this->trainingRepository(), $this->eventDispatcher());
    }

    public function buyTicket(): BuyTicket
    {
        return new BuyTicket(
            $this->trainingRepository(),
            $this->userRepository(),
            $this->ticketRepository(),
            $this->eventDispatcher()
        );
    }

    public function registerAttendee(): RegisterAttendee
    {
        return new RegisterAttendee(
            $this->trainingRepository(),
            $this->eventDispatcher()
        );
    }
}
