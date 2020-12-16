<?php
declare(strict_types=1);

namespace Test\UseCases;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use BehatExpectException\ExpectException;
use DevPro\Application\Training\ScheduleTraining;
use DevPro\Application\Training\UpcomingTraining;
use DevPro\Application\Users\CreateOrganizer;
use DevPro\Domain\Model\User\UserId;
use PHPUnit\Framework\Assert;
use Test\UseCases\Support\UseCaseTestServiceContainer;

final class FeatureContext implements Context
{
    use ExpectException;

    private UseCaseTestServiceContainer $container;
    private ?string $title = null;

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
        $this->title = $title;
        $this->container->application()->scheduleTraining(
            new ScheduleTraining(
                $this->theOrganizer()->asString(),
                'NL',
                $title,
                $date
            )
        );
    }

    /**
     * @Then it shows up on the list of upcoming trainings
     */
    public function itShowsUpOnTheListOfUpcomingTrainings(): void
    {
        Assert::assertNotNull($this->title);

        $allTitles = array_map(
            fn (UpcomingTraining $upcomingTraining) => $upcomingTraining->title(),
            $this->container->application()->findAllUpcomingTrainings()
        );
        Assert::assertContains($this->title, $allTitles);
    }

    private function theOrganizer(): UserId
    {
        return $this->container->application()->createOrganizer(new CreateOrganizer());
    }
}
