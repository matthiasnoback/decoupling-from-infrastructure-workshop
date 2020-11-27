<?php
declare(strict_types=1);

namespace Test\UseCases\PHPUnit;

use DevPro\Application\ScheduleTraining\ScheduleTraining;
use DevPro\Application\Users\CreateOrganizer;
use DevPro\Domain\Model\Common\Country;
use DevPro\Domain\Model\Training\ScheduledDate;
use DevPro\Domain\Model\User\UserId;
use Exception;

final class SchedulingTest extends UseCaseTestCase
{
    /**
     * @test
     */
    public function theOrganizerTriesToScheduleATrainingOnANationalHoliday(): void
    {
        $scheduledDate = ScheduledDate::fromString('2020-12-25 09:30');
        $country = Country::fromString('NL');

        // Given "2020-12-25" is a national holiday in "NL"
        $this->container->nationalHolidays()->markAsNationalHoliday(
            $scheduledDate,
            $country
        );

        // When the organizer tries to schedule a training on "2020-12-25" in "NL"
        try {
            $this->container->application()->scheduleTraining(
                new ScheduleTraining(
                    $this->theOrganizer()->asString(),
                    'A title',
                    $scheduledDate->asString(),
                    $country->asString()
                )
            );
            $this->fail('Expected an exception');
        } catch (Exception $exception) {
            // Then they see a message "The date of the training is a national holiday"
            self::assertEquals('The date of the training is a national holiday', $exception->getMessage());
        }
    }

    /**
     * @test
     */
    public function theOrganizerSchedulesATrainingOnANormalDay(): void
    {
        // Given "2020-12-23" is not a national holiday in "NL"
        // When the organizer tries to schedule a training on "2020-12-23" in "NL"
        // Then this training will be scheduled

        $this->markTestIncomplete('TODO Assignment 7');
    }

    private function theOrganizer(): UserId
    {
        return $this->container->application()->createOrganizer(new CreateOrganizer());
    }
}
