<?php
declare(strict_types=1);

namespace Test\UseCases;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use BehatExpectException\ExceptionExpectationFailed;
use BehatExpectException\ExpectException;
use DevPro\Application\ScheduleTraining;
use DevPro\Application\Users\CreateOrganizer;
use DevPro\Application\Users\CreateUser;
use DevPro\Domain\Model\Training\CouldNotScheduleTraining;
use DevPro\Domain\Model\Training\TrainingWasScheduled;
use DevPro\Domain\Model\User\UserId;
use PHPUnit\Framework\Assert;
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

    /**
     * @Given :date is a national holiday in :country
     */
    public function isANationalHolidayIn(string $date, string $country): void
    {
        $this->container->nationalHolidays()->thisIsANationalHolidayIn($date, $country);
    }

    /**
     * @Given :date is not a national holiday in :country
     */
    public function isNotANationalHolidayIn(string $date, string $country): void
    {
        $this->container->nationalHolidays()->thisIsNotANationalHolidayIn($date, $country);
    }

    /**
     * @When the organizer tries to schedule a training on :date in :country
     */
    public function theOrganizerTriesToScheduleATrainingOnIn(string $date, string $country): void
    {
        $this->mayFail(
            function () use ($country, $date) {
                $this->container->application()->scheduleTraining(
                    new ScheduleTraining(
                        $this->theOrganizer()->asString(),
                        'Irrelevant title',
                        $date,
                        $country
                    )
                );
            }
        );
    }

    /**
     * @Then they see a message :message
     */
    public function theySeeAMessage(string $message): void
    {
        if (!$this->caughtException instanceof CouldNotScheduleTraining) {
            throw new ExceptionExpectationFailed('No exception was caught. Call $this->shouldFail() or $this->mayFail() first');
        }

        Assert::assertEquals($message, $this->caughtException->userFacingMessage());
    }

    /**
     * @Then this training will be scheduled
     */
    public function thisTrainingWillBeScheduled(): void
    {
        foreach ($this->container->dispatchedEvents() as $event) {
            if ($event instanceof TrainingWasScheduled) {
                return;
            }
        }

        throw new \RuntimeException('No training was scheduled');
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
