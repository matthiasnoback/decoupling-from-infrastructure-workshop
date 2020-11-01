<?php
declare(strict_types=1);

namespace DevPro\Infrastructure;

use Assert\Assert;
use Common\EventDispatcher\EventDispatcher;
use DevPro\Application\Clock;
use DevPro\Domain\Model\Ticket\TicketRepository;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Domain\Model\User\UserRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

final class DevelopmentAbstractServiceContainer extends AbstractServiceContainer
{
    private string $projectRootDir;
    private ?Connection $connection;

    public function __construct(string $projectRootDir)
    {
        Assert::that($projectRootDir)->directory();
        $this->projectRootDir = $projectRootDir;
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
                    'path' => $this->projectRootDir . '/var/app.sqlite'
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
        throw new \BadMethodCallException('Not implemented yet');
    }

    protected function trainingRepository(): TrainingRepository
    {
        throw new \BadMethodCallException('Not implemented yet');
    }

    protected function ticketRepository(): TicketRepository
    {
        throw new \BadMethodCallException('Not implemented yet');
    }
}
