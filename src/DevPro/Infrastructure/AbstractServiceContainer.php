<?php
declare(strict_types=1);

namespace DevPro\Infrastructure;

use Assert\Assert;
use BadMethodCallException;
use Common\EventDispatcher\EventDispatcher;
use DevPro\Application\Application;
use DevPro\Application\ApplicationInterface;
use DevPro\Application\Clock;
use DevPro\Application\Users\CreateOrganizerHandler;
use DevPro\Application\Users\CreateUserHandler;
use DevPro\Application\Users\SecurityUsers;
use DevPro\Domain\Model\Ticket\TicketRepository;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Domain\Model\User\UserRepository;
use DevPro\Infrastructure\Database\SchemaManager;
use DevPro\Infrastructure\Database\SecurityUsersUsingDbal;
use DevPro\Infrastructure\Database\UserRepositoryUsingDbal;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

abstract class AbstractServiceContainer implements ServiceContainer
{
    private ?Connection $connection = null;
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
        $this->schemaManager()->updateSchema();
    }

    public function application(): ApplicationInterface
    {
        return new Application(
            $this->createUserHandler(),
            $this->createOrganizerHandler()
        );
    }

    private function eventDispatcher(): EventDispatcher
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

    private function connection(): Connection
    {
        if (!$this->connection instanceof Connection) {
            $this->connection = DriverManager::getConnection(
                [
                    'driver' => 'pdo_sqlite',
                    'path' => $this->containerConfiguration->varDirectory() . '/' . $this->environment() . '.sqlite'
                ]
            );
        }

        return $this->connection;
    }

    private function schemaManager(): SchemaManager
    {
        return new SchemaManager($this->connection());
    }

    protected function userRepository(): UserRepository
    {
        return new UserRepositoryUsingDbal($this->connection());
    }

    protected function trainingRepository(): TrainingRepository
    {
        throw new BadMethodCallException('Not implemented yet');
    }

    protected function ticketRepository(): TicketRepository
    {
        throw new BadMethodCallException('Not implemented yet');
    }

    protected function securityUsers(): SecurityUsers
    {
        return new SecurityUsersUsingDbal($this->connection());
    }

    private function createUserHandler(): CreateUserHandler
    {
        return new CreateUserHandler($this->userRepository(), $this->eventDispatcher());
    }

    private function createOrganizerHandler(): CreateOrganizerHandler
    {
        return new CreateOrganizerHandler($this->userRepository(), $this->eventDispatcher());
    }
}
