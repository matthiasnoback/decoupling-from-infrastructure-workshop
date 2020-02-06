<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use BehatExpectException\ExpectException;
use DevPro\Application\ScheduleTraining;
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
     * @var UserId
     */
    private $organiserId;

    public function __construct()
    {
        $this->container = new TestServiceContainer();
        $this->userRepository = $this->container->userRepository();
        $this->trainingRepository = $this->container->trainingRepository();
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
        $service = new ScheduleTraining($this->trainingRepository, $this->userRepository, $this->container->eventDispatcher());

        $service->scheduleTraining($title, $date, $this->organiserId);
    }

    /**
     * @Then it shows up on the list of upcoming events
     */
    public function itShowsUpOnTheListOfUpcomingEvents(): void
    {
        throw new PendingException();
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
