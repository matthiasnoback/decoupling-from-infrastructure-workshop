<?php
declare(strict_types=1);

namespace DevPro\Infrastructure;

use Assert\Assert;
use Common\EventDispatcher\EventDispatcher;
use DevPro\Application\Application;
use DevPro\Application\ApplicationInterface;
use DevPro\Application\Clock;
use DevPro\Application\Users\CreateOrganizerHandler;
use DevPro\Application\Users\CreateUserHandler;
use DevPro\Domain\Model\Ticket\TicketRepository;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Domain\Model\User\UserRepository;

abstract class AbstractServiceContainer implements ServiceContainer
{
    private ?EventDispatcher $eventDispatcher = null;
    protected ContainerConfiguration $containerConfiguration;

    abstract protected function clock(): Clock;

    public function __construct(ContainerConfiguration $containerConfiguration)
    {
        $this->containerConfiguration = $containerConfiguration;

        $this->boot();
    }

    protected function environment(): string
    {
        return $this->containerConfiguration->environment();
    }

    public function boot(): void
    {
    }

    public function application(): ApplicationInterface
    {
        return new Application($this);
    }

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

    abstract protected function userRepository(): UserRepository;

    abstract protected function trainingRepository(): TrainingRepository;

    abstract protected function ticketRepository(): TicketRepository;

    public function createUser(): CreateUserHandler
    {
        return new CreateUserHandler($this->userRepository(), $this->eventDispatcher());
    }

    public function createOrganizer(): CreateOrganizerHandler
    {
        return new CreateOrganizerHandler($this->userRepository(), $this->eventDispatcher());
    }
}
