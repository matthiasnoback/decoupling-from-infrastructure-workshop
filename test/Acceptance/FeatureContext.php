<?php
declare(strict_types=1);

namespace Test\Acceptance;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use BehatExpectException\ExpectException;
use DevPro\Domain\Model\User\UserId;
use Test\Acceptance\Support\TestServiceContainer;

final class FeatureContext implements Context
{
    use ExpectException;

    /**
     * @var TestServiceContainer
     */
    private $container;

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
        $this->container->createTraining()->create(
            $this->theOrganizer(),
            $title,
            date_create_immutable_from_format("d-m-Y", $date)
        );
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
