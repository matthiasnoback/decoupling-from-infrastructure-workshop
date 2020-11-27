<?php
declare(strict_types=1);

namespace Test\UseCases;

use Behat\Behat\Context\Context;
use BehatExpectException\ExpectException;
use DevPro\Application\ScheduleTraining\ScheduleTraining;
use DevPro\Application\Users\CreateOrganizer;
use DevPro\Application\Users\CreateUser;
use DevPro\Domain\Model\Common\Country;
use DevPro\Domain\Model\Training\ScheduledDate;
use DevPro\Domain\Model\User\UserId;
use PHPUnit\Framework\Assert;
use Test\UseCases\Support\UseCaseTestServiceContainer;

final class FeatureContext implements Context
{
    use ExpectException;

    private UseCaseTestServiceContainer $container;
    private ?string $titleOfScheduledTraining = null;

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
        $this->titleOfScheduledTraining = $title;

        $this->container->application()->scheduleTraining(
            new ScheduleTraining(
                $this->theOrganizer()->asString(),
                $title,
                $date,
                'NL' // irrelevant for the test
            )
        );
    }

    /**
     * @Then it shows up on the list of upcoming trainings
     */
    public function itShowsUpOnTheListOfUpcomingTrainings(): void
    {
        $expectedTitle = $this->titleOfScheduledTraining;
        Assert::assertNotNull($expectedTitle);

        foreach ($this->container->application()->findAllUpcomingTrainings() as $upcomingTraining) {
            if ($upcomingTraining->title() === $expectedTitle) {
                return;
            }
        }

        throw new \RuntimeException(sprintf('Expected to find an upcoming training with title "%s"', $expectedTitle));
    }

    /**
     * @Given :date" is a national holiday in :country
     */
    public function givenDateIsANationalHolidayInCountry(string $date, string $country): void
    {
        $this->container->nationalHolidays()->markAsNationalHoliday(
            ScheduledDate::fromString($date . ' 09:30'),
            Country::fromString($country)
        );
    }

    /**
     * @When the organizer tries to schedule a training on :date in :country
     */
    public function theOrganizerTriesToScheduleATrainingOnDateInCountry(string $date, string $country): void
    {
        $this->shouldFail(
            function () use ($country, $date) {
                $this->container->application()->scheduleTraining(
                    new ScheduleTraining(
                        $this->theOrganizer()->asString(),
                        'A title',
                        $date . ' 09:30',
                        $country
                    )
                );
            }
        );
    }

    /**
     * @Then they see a message :exceptionMessage
     */
    public function theySeeAMessage(string $message): void
    {
        Assert::assertNotNull($this->caughtException);

        Assert::assertEquals($message, $this->caughtException->getMessage());
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
