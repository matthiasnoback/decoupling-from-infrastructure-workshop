<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Assert\Assert;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use BehatExpectException\ExpectException;
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
