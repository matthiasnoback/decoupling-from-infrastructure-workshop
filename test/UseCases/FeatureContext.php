<?php
declare(strict_types=1);

namespace Test\UseCases;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use BehatExpectException\ExpectException;
use DevPro\Application\Users\CreateOrganizer;
use DevPro\Domain\Model\User\UserId;
use Test\UseCases\Support\UseCaseTestServiceContainer;

final class FeatureContext implements Context
{
    use ExpectException;

    private UseCaseTestServiceContainer $container;

    public function __construct()
    {
        $this->container = UseCaseTestServiceContainer::create();
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
        throw new PendingException();
    }

    /**
     * @Then it shows up on the list of upcoming trainings
     */
    public function itShowsUpOnTheListOfUpcomingTrainings(): void
    {
        throw new PendingException();
    }

    private function theOrganizer(): UserId
    {
        return $this->container->application()->createOrganizer(new CreateOrganizer());
    }
}
