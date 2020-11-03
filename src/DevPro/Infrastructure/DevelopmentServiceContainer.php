<?php
declare(strict_types=1);

namespace DevPro\Infrastructure;

use Assert\Assert;
use BadMethodCallException;
use Common\EventDispatcher\EventDispatcher;
use DevPro\Application\Clock;
use DevPro\Application\Users\GetSecurityUser;
use DevPro\Domain\Model\Ticket\TicketRepository;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Domain\Model\User\UserRepository;
use DevPro\Infrastructure\Database\GetSecurityUserUsingDbal;
use DevPro\Infrastructure\Database\SchemaManager;
use DevPro\Infrastructure\Database\UserRepositoryUsingDbal;
use DevPro\Infrastructure\Framework\TemplateRenderer;
use DevPro\Infrastructure\Web\Controllers;
use DevPro\Infrastructure\Web\WebApplication;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Test\Adapter\DevPro\Infrastructure\InputAdapterTestServiceContainer;
use Test\EndToEnd\EndToEndTestServiceContainer;

/**
 * Not final but also not abstract because we want to be able to override some methods, yet use this as the actual
 * development service container.
 */
class DevelopmentServiceContainer extends AbstractServiceContainer
{
    protected string $varDirectory;
    private ?Connection $connection = null;
    private ?UserRepositoryUsingDbal $userRepository = null;
    private ?GetSecurityUserUsingDbal $getSecurityUser = null;
    private ?Session $session = null;

    public function __construct(string $varDirectory, string $environment)
    {
        Assert::that($varDirectory)->directory();
        $this->varDirectory = $varDirectory;

        parent::__construct($environment);
    }

    public static function createForEnvironment(string $varDirectory, string $environment): self
    {
        if ($environment === 'input_adapter_test') {
            return new InputAdapterTestServiceContainer();
        } elseif ($environment === 'end_to_end') {
            return new EndToEndTestServiceContainer();
        }

        return new self($varDirectory, $environment);
    }

    public function boot(): void
    {
        parent::boot();

        $this->schemaManager()->updateSchema();
    }

    private function controllers(): Controllers
    {
        return new Controllers(
            $this->application(),
            $this->getSecurityUser(),
            $this->session(),
            $this->templateRenderer()
        );
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
                    'path' => $this->varDirectory . '/' . $this->environment() . '.sqlite'
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

    public function getSecurityUser(): GetSecurityUser
    {
        return $this->getSecurityUser ?? $this->getSecurityUser = new GetSecurityUserUsingDbal($this->connection());
    }

    private function templateRenderer(): TemplateRenderer
    {
        return new TemplateRenderer($this->globalTemplateVariables());
    }

    /**
     * @return array<string,mixed>
     */
    private function globalTemplateVariables(): array
    {
        return [
            'session' => $this->session()
        ];
    }

    private function session(): Session
    {
        return $this->session ?? $this->session = new Session();
    }

    public function webApplication(): WebApplication
    {
        return WebApplication::createFromGlobalsWithControllers($this->controllers());
    }
}
