<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use Assert\Assert;
use Common\EventDispatcher\EventDispatcher;
use MeetupOrganizing\Application\Application;
use MeetupOrganizing\Application\ApplicationInterface;
use MeetupOrganizing\Application\Clock;
use MeetupOrganizing\Application\Meetups\MeetupDetailsRepository;
use MeetupOrganizing\Application\Meetups\RsvpToMeetupHandler;
use MeetupOrganizing\Application\Meetups\ScheduleMeetupHandler;
use MeetupOrganizing\Application\Meetups\UpcomingMeetupRepository;
use MeetupOrganizing\Application\Users\CreateUserHandler;
use MeetupOrganizing\Application\Users\Users;
use MeetupOrganizing\Domain\Model\Common\NationalHoliday;
use MeetupOrganizing\Infrastructure\Holidays\NationalHolidayApiClient;
use MeetupOrganizing\Domain\Model\Meetup\MeetupRepository;
use MeetupOrganizing\Domain\Model\User\UserRepository;
use MeetupOrganizing\Infrastructure\Database\MeetupDetailsRepositoryUsingDbal;
use MeetupOrganizing\Infrastructure\Database\SchemaManager;
use MeetupOrganizing\Infrastructure\Database\UsersUsingDbal;
use MeetupOrganizing\Infrastructure\Database\MeetupRepositoryUsingDbal;
use MeetupOrganizing\Infrastructure\Database\UpcomingMeetupRepositoryUsingDbal;
use MeetupOrganizing\Infrastructure\Database\UserRepositoryUsingDbal;
use MeetupOrganizing\Infrastructure\Holidays\AbstractApiClient;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;

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
            $this->scheduleMeetupHandler(),
            $this->upcomingMeetupRepository(),
            $this->meetupDetailsRepository(),
            new RsvpToMeetupHandler($this->userRepository(), $this->meetupRepository())
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

    protected function schemaManager(): SchemaManager
    {
        return new SchemaManager($this->connection());
    }

    protected function userRepository(): UserRepository
    {
        return new UserRepositoryUsingDbal($this->connection());
    }

    protected function meetupRepository(): MeetupRepository
    {
        return new MeetupRepositoryUsingDbal($this->connection());
    }

    protected function securityUsers(): Users
    {
        return new UsersUsingDbal($this->connection());
    }

    private function upcomingMeetupRepository(): UpcomingMeetupRepository
    {
        return new UpcomingMeetupRepositoryUsingDbal($this->connection(), $this->clock());
    }

    private function meetupDetailsRepository(): MeetupDetailsRepository
    {
        return new MeetupDetailsRepositoryUsingDbal($this->connection());
    }

    private function createUserHandler(): CreateUserHandler
    {
        return new CreateUserHandler($this->userRepository(), $this->eventDispatcher());
    }

    private function scheduleMeetupHandler(): ScheduleMeetupHandler
    {
        return new ScheduleMeetupHandler(
            $this->meetupRepository(),
            $this->userRepository(),
            $this->nationalHoliday()
        );
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

    protected function nationalHoliday(): NationalHoliday
    {
        return new NationalHolidayApiClient($this->abstractApiClient());
    }
}
