<?php
declare(strict_types=1);

namespace DevPro\Infrastructure;

use BadMethodCallException;
use Common\EventDispatcher\EventDispatcher;
use DevPro\Application\Clock;
use DevPro\Application\ListUpcomingTrainings;
use DevPro\Application\Users\GetSecurityUser;
use DevPro\Domain\Model\Ticket\TicketRepository;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Domain\Model\User\UserRepository;
use DevPro\Domain\Service\NationalHolidays;
use DevPro\Infrastructure\Database\GetSecurityUserUsingDbal;
use DevPro\Infrastructure\Database\SchemaManager;
use DevPro\Infrastructure\Database\TrainingRepositoryUsingDbal;
use DevPro\Infrastructure\Database\UserRepositoryUsingDbal;
use DevPro\Infrastructure\Framework\TemplateRenderer;
use DevPro\Infrastructure\Holidays\AbstractApiClient;
use DevPro\Infrastructure\Holidays\NationalHolidaysUsingAbstractApi;
use DevPro\Infrastructure\Web\Controllers;
use DevPro\Infrastructure\Web\WebApplication;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use Test\Adapter\DevPro\Infrastructure\InputAdapterTestServiceContainer;
use Test\Adapter\DevPro\Infrastructure\OutputAdapterTestServiceContainer;
use Test\EndToEnd\EndToEndTestServiceContainer;

/**
 * Not final but also not abstract because we want to be able to override some methods, yet use this as the actual
 * development service container.
 */
abstract class AbstractDevelopmentServiceContainer extends AbstractServiceContainer
{
    private ?Connection $connection = null;
    private ?UserRepositoryUsingDbal $userRepository = null;
    private ?GetSecurityUserUsingDbal $getSecurityUser = null;
    private ?Session $session = null;

    public function boot(): void
    {
        parent::boot();

        $this->schemaManager()->updateSchema();
    }

    public static function create(ContainerConfiguration $containerConfiguration): self
    {
        if ($containerConfiguration->environment() === 'input_adapter_test') {
            return new InputAdapterTestServiceContainer($containerConfiguration);
        } elseif ($containerConfiguration->environment() === 'end_to_end') {
            return new EndToEndTestServiceContainer($containerConfiguration);
        } elseif ($containerConfiguration->environment() === 'output_adapter_test') {
            return new OutputAdapterTestServiceContainer($containerConfiguration);
        }

        return new DevelopmentServiceContainer($containerConfiguration);
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
        return $this->userRepository ?? $this->userRepository = new UserRepositoryUsingDbal($this->connection());
    }

    protected function trainingRepository(): TrainingRepository
    {
        return new TrainingRepositoryUsingDbal($this->connection());
    }

    protected function ticketRepository(): TicketRepository
    {
        throw new BadMethodCallException('Not implemented yet');
    }

    public function listUpcomingTrainings(): ListUpcomingTrainings
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

    protected function abstractApiClient(): AbstractApiClient
    {
        $handlerStack = HandlerStack::create($this->guzzleHttpHandler());

        return new AbstractApiClient(
            new Client(
                [
                    'handler' => $handlerStack,
                    'http_errors' => false
                ]
            ),
            $this->abstractApiBaseUrl(),
            $this->containerConfiguration->abstractApiApiKey()
        );
    }

    protected function guzzleHttpHandler(): callable
    {
        return new CurlHandler();
    }

    protected function abstractApiBaseUrl(): string
    {
        return 'https://holidays.abstractapi.com/v1/';
    }

    protected function nationalHolidays(): NationalHolidays
    {
        return new NationalHolidaysUsingAbstractApi($this->abstractApiClient());
    }
}
