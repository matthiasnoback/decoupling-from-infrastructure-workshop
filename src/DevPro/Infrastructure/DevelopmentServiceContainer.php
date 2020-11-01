<?php
declare(strict_types=1);

namespace DevPro\Infrastructure;

use Assert\Assert;
use BadMethodCallException;
use Common\EventDispatcher\EventDispatcher;
use DevPro\Application\Clock;
use DevPro\Domain\Model\Ticket\TicketRepository;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Domain\Model\User\UserRepository;
use DevPro\Infrastructure\Database\SchemaManager;
use DevPro\Infrastructure\Database\UserRepositoryUsingDbal;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

/**
 * Not final but also not abstract because we want to be able to override some methods, yet use this as the actual
 * development service container.
 */
class DevelopmentServiceContainer extends AbstractServiceContainer
{
    private string $projectRootDir;
    private ?Connection $connection = null;
    private ?UserRepositoryUsingDbal $userRepository = null;
    private string $varDir;

    protected function environment(): string
    {
        return 'development';
    }

    public function __construct(string $projectRootDir)
    {
        Assert::that($projectRootDir)->directory();
        $this->projectRootDir = $projectRootDir;

        $this->varDir = $projectRootDir . '/var';
        if (!is_dir($this->varDir)) {
            mkdir($this->varDir);
        }
    }

    public function boot(): void
    {
        parent::boot();

        $this->schemaManager()->updateSchema();
    }

    protected function clock(): Clock
    {
        return new SystemClock();
    }

    protected function registerSubscribers(EventDispatcher $eventDispatcher): void
    {
        // Register subscribers that should be available in every environment in the parent method
        parent::registerSubscribers($eventDispatcher);

        // Register additional event subscribers that are only meant to be notified in a development environment:
    }

    private function connection(): Connection
    {
        if (!$this->connection instanceof Connection) {
            $this->connection = DriverManager::getConnection(
                [
                    'driver' => 'pdo_sqlite',
                    'path' => $this->varDir . '/' . $this->environment() . '.sqlite'
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
        return $this->userRepository ?? $this->userRepository = new UserRepositoryUsingDbal($this->connection());
    }

    protected function trainingRepository(): TrainingRepository
    {
        throw new BadMethodCallException('Not implemented yet');
    }

    protected function ticketRepository(): TicketRepository
    {
        throw new BadMethodCallException('Not implemented yet');
    }
}
