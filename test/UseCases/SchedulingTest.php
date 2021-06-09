<?php
declare(strict_types=1);

namespace Test\UseCases;

use DevPro\Application\ScheduleTraining;
use DevPro\Application\Users\CreateOrganizer;
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
        $this->container->application()->scheduleTraining(
            new ScheduleTraining(
                $this->theOrganizer()->asString(),
                'NL',
                'Decoupling from infrastructure',
                '2020-01-24 09:30'
            )
        );

        // Then it shows up on the list of upcoming trainings
        $this->markTestIncomplete('TODO Assignment 5');
    }

    /**
     * @test
     */
    public function theOrganizerTriesToScheduleATrainingOnANationalHoliday(): void
    {
        // Given "2020-12-25" is a national holiday in "NL"

        // When the organizer tries to schedule a training on this date in this country

        // Then they see a message "The date of the training is a national holiday"

        $this->markTestIncomplete('TODO Assignment 6');
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
}
