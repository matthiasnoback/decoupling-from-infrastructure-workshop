<?php
declare(strict_types=1);

namespace Test\UseCases\PHPUnit;

use DevPro\Application\Trainings\ScheduleTraining;
use DevPro\Application\Users\CreateOrganizer;
use DevPro\Domain\Model\Training\CouldNotScheduleTraining;
use DevPro\Domain\Model\User\UserId;

final class SchedulingTest extends UseCaseTestCase
{
    /**
     * @test
     */
    public function theOrganizerTriesToScheduleATrainingOnANationalHoliday(): void
    {
        // Given "2020-12-25" is a national holiday in "NL"
        $country = 'NL';
        $date = '2020-12-25';
        $this->container->nationalHolidays()->markAsNationalHoliday(
            $country,
            $date
        );

        $this->expectException(CouldNotScheduleTraining::class);
        $this->expectExceptionMessage('The date of the training is a national holiday');

        $this->container->application()->scheduleTraining(
            new ScheduleTraining(
                $this->theOrganizer()->asString(),
                $country,
                'A title',
                $date . ' 09:30'
            )
        );
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
