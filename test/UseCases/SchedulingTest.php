<?php
declare(strict_types=1);

namespace Test\UseCases;

use DevPro\Application\ScheduleTraining;
use DevPro\Application\UpcomingTraining;
use DevPro\Application\Users\CreateOrganizer;
use DevPro\Domain\Model\Training\CouldNotScheduleTraining;
use DevPro\Domain\Model\User\UserId;
use Exception;
use PHPUnit\Framework\AssertionFailedError;

final class SchedulingTest extends AbstractUseCaseTestCase
{
    protected function setUp(): void
    {
        $this->container->setCurrentDate('2020-01-01');
    }

    /**
     * @test
     */
    public function aScheduledTrainingShowsUpInUpcomingTrainings(): void
    {
        // When the organizer schedules a new training called "Decoupling from infrastructure" for "2020-01-24 09:30"
        $title = 'Decoupling from infrastructure';
        $this->container->application()->scheduleTraining(
            new ScheduleTraining(
                $this->theOrganizer()->asString(),
                'NL',
                $title,
                '2020-01-24 09:30'
            )
        );

        // Then it shows up on the list of upcoming trainings
        $upcomingTrainingTitles = array_map(
            fn (UpcomingTraining $upcomingTraining) => $upcomingTraining->title(),
            $this->container->application()->findAllUpcomingTrainings()
        );
        self::assertContains($title, $upcomingTrainingTitles);
    }

    /**
     * @test
     */
    public function theScheduledDateOfTheTrainingIsInThePast(): void
    {
        // Given the organizer has scheduled a training on "2020-01-24 09:30"
        $theTitle = 'The title';

        $this->container->application()->scheduleTraining(
            new ScheduleTraining(
                $this->theOrganizer()->asString(),
                $this->aCountry(),
                $theTitle,
                '2020-01-24 09:30'
            )
        );

        // When today is "2020-02-01"
        $this->container->setCurrentDate('2020-02-01');

        // Then it no longer shows up on the list of upcoming trainings
        $upcomingTrainingTitles = array_map(
            fn (UpcomingTraining $upcomingTraining) => $upcomingTraining->title(),
            $this->container->application()->findAllUpcomingTrainings()
        );
        self::assertNotContains($theTitle, $upcomingTrainingTitles);
    }

    /**
     * @test
     */
    public function theOrganizerTriesToScheduleATrainingOnANationalHoliday(): void
    {
        // Given "2020-12-25" is a national holiday in "NL"
        $date = '2020-12-25';
        $country = 'NL';
        $this->container->nationalHolidays()->markAsHoliday($date, $country);

        // When the organizer tries to schedule a training on this date in this country
        try {
            $this->container->application()->scheduleTraining(
                new ScheduleTraining(
                    $this->theOrganizer()->asString(),
                    $country,
                    $this->aTitle(),
                    $date . ' 09:30'
                )
            );
            $this->fail('Expected this to fail');
        } catch (AssertionFailedError $exception) {
            throw $exception;
        }
        catch (Exception $exception) {
            self::assertInstanceOf(CouldNotScheduleTraining::class, $exception);
            // Then they see a message "The date of the training is a national holiday"
            self::assertStringContainsString(
                'The date of the training is a national holiday',
                $exception->getMessage()
            );
        }
    }

    /**
     * @test
     */
    public function theOrganizerSchedulesATrainingOnANormalDay(): void
    {
        // Given "2020-12-23" is not a national holiday in "NL"
        // When the organizer tries to schedule a training on this date in this country
        // Then this training will be scheduled

        $this->markTestIncomplete('TODO Assignment 7');
    }

    private function theOrganizer(): UserId
    {
        return $this->container->application()->createOrganizer(new CreateOrganizer());
    }

    private function aCountry(): string
    {
        return 'NL';
    }

    private function aTitle(): string
    {
        return 'A title';
    }
}
