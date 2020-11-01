<?php
declare(strict_types=1);

namespace DevPro\Infrastructure;

use Assert\Assert;
use Common\EventDispatcher\EventDispatcher;
use DevPro\Application\Clock;
use DevPro\Application\CreateUser;
use DevPro\Domain\Model\Ticket\TicketRepository;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Domain\Model\User\UserRepository;
use Test\Acceptance\Support\InMemoryTicketRepository;
use Test\Acceptance\Support\InMemoryTrainingRepository;
use Test\Acceptance\Support\InMemoryUserRepository;

abstract class AbstractServiceContainer
{
    private ?EventDispatcher $eventDispatcher;

    private ?InMemoryUserRepository $userRepository;
    private ?InMemoryTrainingRepository $trainingRepository;
    private ?InMemoryTicketRepository $ticketRepository;

    abstract protected function clock(): Clock;

    public function eventDispatcher(): EventDispatcher
    {
        if ($this->eventDispatcher === null) {
            /*
             * We assign the event dispatcher to the property directly so event subscribers can have the event
             * dispatcher as a dependency too.
             */
            $this->eventDispatcher = new EventDispatcher();

            $this->registerSubscribers($this->eventDispatcher);

            // Though in practice this won't happen, theoretically $this->eventDispatcher could have been set to null, so:
            Assert::that($this->eventDispatcher)->isInstanceOf(EventDispatcher::class);
        }

        return $this->eventDispatcher;
    }

    protected function registerSubscribers(EventDispatcher $eventDispatcher): void
    {
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

    public function createUser(): CreateUser
    {
        return new CreateUser($this->userRepository(), $this->eventDispatcher());
    }
}
