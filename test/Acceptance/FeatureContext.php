<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use BehatExpectException\ExpectException;
use DevPro\Application\UpcomingEvent;
use DevPro\Domain\Model\User\UserId;
use PHPUnit\Framework\Assert;
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
    private $titleOfScheduledTraining = null;

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
        $this->titleOfScheduledTraining = $title;

        $this->container->scheduleTraining()->schedule(
            $this->theOrganizer()->asString(),
            $title,
            $date
        );
    }

    /**
     * @Then it shows up on the list of upcoming events
     */
    public function itShowsUpOnTheListOfUpcomingEvents(): void
    {
        $titles = array_map(function (UpcomingEvent $event): string {
            return $event->title();
        }, $this->container->upcomingEvents()->findAll());

        Assert::assertContains($this->titleOfScheduledTraining, $titles);
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
