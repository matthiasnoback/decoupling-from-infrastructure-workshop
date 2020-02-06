<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Assert\Assert;
use Behat\Behat\Context\Context;
use BehatExpectException\ExpectException;
use DevPro\Application\EventForList;
use DevPro\Application\ScheduleTraining;
use DevPro\Application\UpcomingEventsRepository;
use DevPro\Domain\Model\Training\AttendeeWasRegistered;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Domain\Model\User\UserId;
use DevPro\Domain\Model\User\UserRepository;
use RuntimeException;
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

    /**
     * @var string | null
     */
    private $trainingId;

    /**
     * @var string | null
     */
    private $userId;

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
        $service = new ScheduleTraining(
            $this->trainingRepository,
            $this->userRepository,
            $this->container->eventDispatcher());

        $trainingId = $service->scheduleTraining($title, $date, $this->organiserId);

        $this->trainingId = $trainingId->asString();
    }

    /**
     * @Then it shows up on the list of upcoming events
     */
    public function itShowsUpOnTheListOfUpcomingEvents(): void
    {
        $list = $this->upcomingEventsRepository->list($this->container->clock()->currentTime());
        $title = $this->trainingTitle;
        $result = array_filter(
            $list,
            function (EventForList $event) use ($title) {
                return $event->name === $title;
            });

        assertGreaterThanOrEqual(1, count($result));
    }

    private function theOrganizer(): UserId
    {
        return $this->container->createUser()->create('The organizer');
    }

    /**
     * @Given the organizer has scheduled a training
     */
    public function theOrganizerHasScheduledATraining()
    {
        $this->theOrganizerSchedulesANewTrainingCalledFor('A title', '06-02-2020');
    }

    /**
     * @When a user buys a ticket for this training
     */
    public function aUserBuysATicketForThisTraining()
    {
        Assert::that($this->trainingId)->string();

        $this->container->buyTicket()->buyTicket($this->aUser()->asString(), $this->trainingId);
    }

    private function aUser(): UserId
    {
        $userId = $this->container->createUser()->create('A user');

        $this->userId = $userId->asString();

        return $userId;
    }

    /**
     * @Then they should be registered as an attendee
     */
    public function theyShouldBeRegisteredAsAnAttendee(): void
    {
        $dispatchedEvents = $this->container->eventSubscriberSpy()->dispatchedEvents();

        foreach ($dispatchedEvents as $dispatchedEvent) {
            if ($dispatchedEvent instanceof AttendeeWasRegistered) {
                assertEquals($this->trainingId, $dispatchedEvent->trainingId()->asString());
                assertEquals($this->userId, $dispatchedEvent->userId()->asString());
                return;
            }
        }

        throw new RuntimeException(
            'User ' . $this->userId . ' was not registered as an attendee for training ' . $this->trainingId
        );
    }
}
