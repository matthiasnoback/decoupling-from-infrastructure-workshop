<?php
declare(strict_types=1);

namespace Test\UseCases;

use DevPro\Application\ScheduleTraining;
use DevPro\Application\UpcomingTraining;
use DevPro\Application\Users\CreateOrganizer;
use DevPro\Domain\Model\Training\CouldNotScheduleTraining;
use DevPro\Domain\Model\User\UserId;

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
        self::assertContains(
            $title,
            array_map(
                fn(UpcomingTraining $upcomingTraining) => $upcomingTraining->title(),
                $this->container->application()->findAllUpcomingTrainings()
            )
        );
    }

    /**
     * @test
     */
    public function theDateOfTheTrainingIsInThePast(): void
    {
        // Given a training was scheduled for 2020-01-01
        $date = '2020-01-01';
        $title = 'A title';
        $this->container->application()->scheduleTraining(
            new ScheduleTraining(
                $this->theOrganizer()->asString(),
                'NL',
                $title,
                $date . ' 09:30'
            )
        );

        // When today is 2020-01-02
        $this->container->setCurrentDate('2020-01-02');

        // Then the training does not show up in the list of upcoming trainings
        self::assertNotContains(
            $title,
            array_map(
                fn(UpcomingTraining $upcomingTraining) => $upcomingTraining->title(),
                $this->container->application()->findAllUpcomingTrainings()
            )
        );
    }

    /**
     * @test
     */
    public function theOrganizerTriesToScheduleATrainingOnANationalHoliday(): void
    {
        // Given "2020-12-25" is a national holiday in "NL"
        $country = 'NL';
        $date = '2020-12-25';

        $this->expectException(CouldNotScheduleTraining::class);
        $this->expectExceptionMessage('The date of the training is a national holiday');

        // When the organizer tries to schedule a training on this date in this country
        $this->container->application()->scheduleTraining(
            new ScheduleTraining(
                $this->theOrganizer()->asString(),
                $country,
                'A title',
                $date . ' 09:30'
            )
        );

        // Then they see a message "The date of the training is a national holiday"
    }

    private function theOrganizer(): UserId
    {
        return $this->container->application()->createOrganizer(new CreateOrganizer());
    }
}
