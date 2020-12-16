<?php
declare(strict_types=1);

namespace Test\UseCases\PHPUnit;

use DevPro\Application\Training\ScheduleTraining;
use DevPro\Application\Users\CreateOrganizer;
use DevPro\Domain\Model\Training\TrainingWasScheduled;
use PHPUnit\Framework\AssertionFailedError;
use RuntimeException;

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
        $this->container->nationalHolidays()->thisIsANationalHolidayIn(
            $country,
            $date
        );

        // When the organizer tries to schedule a training on this date in this country
        try {
            $organizerId = $this->container->application()->createOrganizer(
                new CreateOrganizer()
            );
            $this->container->application()->scheduleTraining(
                new ScheduleTraining(
                    $organizerId->asString(),
                    $country,
                    'A title',
                    $date . ' 09:30'
                )
            );
            $this->fail('Expected an exception');
        } catch (RuntimeException $exception) {
            if ($exception instanceof AssertionFailedError) {
                throw $exception;
            }

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
        $country = 'NL';
        $date = '2020-12-23';
        $this->container->nationalHolidays()->thisIsNotANationalHolidayIn(
            $country,
            $date
        );

        // When the organizer tries to schedule a training on this date in this country
        $organizerId = $this->container->application()->createOrganizer(
            new CreateOrganizer()
        );
        $this->container->application()->scheduleTraining(
            new ScheduleTraining(
                $organizerId->asString(),
                $country,
                'A title',
                $date . ' 09:30'
            )
        );

        // Then this training will be scheduled
        self::assertContains(
            TrainingWasScheduled::class,
            array_map(
                fn(object $event) => get_class($event),
                $this->container->dispatchedEvents()
            )
        );
    }
}
