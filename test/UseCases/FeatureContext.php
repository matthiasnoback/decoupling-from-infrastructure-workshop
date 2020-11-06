<?php
declare(strict_types=1);

namespace Test\UseCases;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use BehatExpectException\ExpectException;
use DevPro\Application\ScheduleTraining;
use DevPro\Application\Users\CreateOrganizer;
use DevPro\Application\Users\CreateUser;
use DevPro\Domain\Model\User\UserId;
use Test\UseCases\Support\UseCaseTestServiceContainer;

final class FeatureContext implements Context
{
    use ExpectException;

    private UseCaseTestServiceContainer $container;
    private ?string $expectedTitle = null;

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
        $this->expectedTitle = $title;

        $this->container->application()->scheduleTraining(
            new ScheduleTraining(
                $this->theOrganizer()->asString(),
                $title,
                $date,
                'NL' // the country is irrelevant for this test
            )
        );
    }

    /**
     * @Then it shows up on the list of upcoming trainings
     */
    public function itShowsUpOnTheListOfUpcomingTrainings(): void
    {
        foreach ($this->container->application()->listUpcomingTrainings() as $upcomingTraining) {
            if ($upcomingTraining->title() === $this->expectedTitle) {
                return;
            }
        }

        throw new \RuntimeException('There is no upcoming training with the title ' . $this->expectedTitle);
    }

    private function theOrganizer(): UserId
    {
        return $this->container->application()->createOrganizer(new CreateOrganizer());
    }

    private function aUser(): UserId
    {
        return $this->container->application()->createUser(new CreateUser('A user'));
    }
}
