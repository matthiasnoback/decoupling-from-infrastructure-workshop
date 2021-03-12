<?php
declare(strict_types=1);

namespace Test\UseCases\PHPUnit;

final class SchedulingTest extends UseCaseTestCase
{
    /**
     * @test
     */
    public function theOrganizerTriesToScheduleATrainingOnANationalHoliday(): void
    {
        // Given "2020-12-25" is a national holiday in "NL"
        $this->container->nationalHolidays()->markAsNationalHoliday(
            'NL',
            '2020-12-25'
        );

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
}
