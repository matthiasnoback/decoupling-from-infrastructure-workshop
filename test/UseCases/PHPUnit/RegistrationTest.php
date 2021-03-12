<?php
declare(strict_types=1);

namespace Test\UseCases\PHPUnit;

use DevPro\Application\Trainings\ScheduleTraining;
use DevPro\Application\Users\CreateOrganizer;
use DevPro\Domain\Model\User\UserId;

final class RegistrationTest extends UseCaseTestCase
{
    /**
     * @before
     */
    protected function givenTodayIs(): void
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

    private function theOrganizer(): UserId
    {
        return $this->container->application()->createOrganizer(new CreateOrganizer());
    }
}
