<?php
declare(strict_types=1);

namespace Test\UseCases\PHPUnit;

final class RegistrationTest extends UseCaseTestCase
{
    /**
     * @before
     */
    protected function givenTodayIs(): void
    {
        $this->container->setCurrentDate('01-01-2020');
    }

    /**
     * @test
     */
    public function aScheduledTrainingShowsUpInUpcomingTrainings(): void
    {
        // When the organizer schedules a new training called "Decoupling from infrastructure" for "24-01-2020"
        $this->markTestIncomplete('TODO Assignment 1');

        // Then it shows up on the list of upcoming trainings
        $this->markTestIncomplete('TODO Assignment 2');
    }
}
