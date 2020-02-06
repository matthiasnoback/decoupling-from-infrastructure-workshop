<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use BehatExpectException\ExpectException;
use DevPro\Application\EventForList;
use DevPro\Application\ScheduleTraining;
use DevPro\Application\UpcomingEventsRepository;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Domain\Model\User\User;
use DevPro\Domain\Model\User\UserId;
use DevPro\Domain\Model\User\UserRepository;
use Test\Acceptance\Support\InMemoryTrainingRepository;
use Test\Acceptance\Support\InMemoryUserRepository;
use Test\Acceptance\Support\TestServiceContainer;

final class FeatureContext implements Context
{
    use ExpectException;

    /**
     * @var TestServiceContainer
     */
    private $container;

    /**
     * @var TrainingRepository
     */
    private $trainingRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UpcomingEventsRepository
     */
    private $upcomingEventsRepository;

    /**
     * @var UserId
     */
    private $organiserId;

    /**
     * @var string
     */
    private $trainingTitle;

    public function __construct()
    {
        $this->container = new TestServiceContainer();
        $this->userRepository = $this->container->userRepository();
        $this->trainingRepository = $this->container->trainingRepository();
        $this->upcomingEventsRepository = $this->container->upcomingEventsRepository();
    }

    /**
     * @Given today is :date
     */
    public function todayIs(string $date): void
    {
        $this->container->setCurrentDate($date);
    }

    /**
     * @When the organizer schedules a new training called :title for :date
     */
    public function theOrganizerSchedulesANewTrainingCalledFor(string $title, string $date): void
    {
        $this->organiserId = $this->theOrganizer();
        $this->trainingTitle = $title;
        $service = new ScheduleTraining($this->trainingRepository, $this->userRepository, $this->container->eventDispatcher());

        $service->scheduleTraining($title, $date, $this->organiserId);
    }

    /**
     * @Then it shows up on the list of upcoming events
     */
    public function itShowsUpOnTheListOfUpcomingEvents(): void
    {
        $list = $this->upcomingEventsRepository->list($this->container->clock()->currentTime());
        $title = $this->trainingTitle;
        $result = array_filter($list, function(EventForList $event) use ($title) {
            return $event->name === $title;
        });

        assertGreaterThanOrEqual(1, count($result));
    }

    private function theOrganizer(): UserId
    {
        return $this->container->createUser()->create('The organizer');
    }

    private function aUser(): UserId
    {
        return $this->container->createUser()->create('A user');
    }
}
