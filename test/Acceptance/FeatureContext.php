<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Assert\Assert;
use Behat\Behat\Context\Context;
use BehatExpectException\ExpectException;
use DevPro\Domain\Model\Training\AttendeeWasRegistered;
use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\User\User;
use DevPro\Domain\Model\User\UserId;
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
     * @var string | null
     */
    private $title;

    /**
     * @var TrainingId | null
     */
    private $trainingId;

    /**
     * @var UserId | null
     */
    private $userId;

    public function __construct()
    {
        $this->container = new TestServiceContainer();
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
        $this->title = $title;
        $this->container->scheduleTraining()->schedule(
            $this->theOrganizer()->asString(),
            $title,
            $date
        );
    }

    /**
     * @Given the organizer has scheduled a training
     */
    public function theOrganizerHasScheduledATraining()
    {
        $this->trainingId = $this->container->scheduleTraining()->schedule(
            $this->theOrganizer()->asString(),
            'The title',
            '01-01-2020'
        );
    }

    /**
     * @When a user buys a ticket for this training
     */
    public function aUserBuysATicketForThisTraining()
    {
        Assert::that($this->trainingId)->isInstanceOf(TrainingId::class);

        $user = User::create($this->container->userRepository()->nextIdentity());
        $this->container->userRepository()->save($user);
        $this->userId = $user->userId();

        $this->container->buyTicket()->buyForTraining(
            $this->trainingId->asString(),
            $user->userId()->asString()
        );
    }

    /**
     * @Then they should be registered as an attendee
     */
    public function theyShouldBeRegisteredAsAnAttendee()
    {
        Assert::that($this->trainingId)->isInstanceOf(TrainingId::class);
        Assert::that($this->userId)->isInstanceOf(UserId::class);

        foreach ($this->container->eventSubscriberSpy()->dispatchedEvents() as $event) {
            if ($event instanceof AttendeeWasRegistered
                && $event->trainingId()->equals($this->trainingId)
                && $event->userId()->equals($this->userId)) {
                return;
            }
        }

        throw new RuntimeException('Expected an AttendeeWasRegistered event');
    }

    /**
     * @Then it shows up on the list of upcoming events
     */
    public function itShowsUpOnTheListOfUpcomingEvents(): void
    {
        Assert::that($this->title)->string();

        foreach ($this->container->listUpcomingEvents()->listUpcomingEvents() as $upcomingEvent) {
            if ($upcomingEvent->title() === $this->title) {
                return;
            }
        }

        throw new RuntimeException('We did not find the scheduled training on the list of upcoming events');
    }

    private function theOrganizer(): UserId
    {
        return UserId::fromString('bb235de9-c15d-4bd8-9bc3-d31e4cc0e96f');
    }

    private function aUser(): UserId
    {
        return UserId::fromString('ce3270f0-c454-4046-b9f8-3280db052892');
    }
}
