<?php
declare(strict_types=1);

namespace Test\UseCases\PHPUnit;

use DevPro\Application\Trainings\ScheduleTraining;
use DevPro\Application\Trainings\UpcomingTraining;
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

    private function theOrganizer(): UserId
    {
        return $this->container->application()->createOrganizer(new CreateOrganizer());
    }
}
