<?php
declare(strict_types=1);

namespace Test\UseCases\PHPUnit;

use DevPro\Application\ScheduleTraining\ScheduleTraining;
use DevPro\Application\UpcomingTrainings\UpcomingTraining;
use DevPro\Application\Users\CreateOrganizer;
use DevPro\Application\Users\CreateUser;
use DevPro\Domain\Model\User\UserId;
use PHPUnit\Framework\Assert;

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
        // When the organizer schedules a new training called "Decoupling from infrastructure" for "2020-01-24 09:30"
        $title = 'Decoupling from infrastructure';

        $this->container->application()->scheduleTraining(
            new ScheduleTraining(
                $this->theOrganizer()->asString(),
                $title,
                '2020-01-24 09:30',
                'NL' // irrelevant for the test
            )
        );

        // Then it shows up on the list of upcoming trainings
        $actualTitles = array_map(
            fn(UpcomingTraining $upcomingTraining) => $upcomingTraining->title(),
            $this->container->application()->findAllUpcomingTrainings()
        );

        self::assertContainsEquals($title, $actualTitles);
    }

    private function theOrganizer(): UserId
    {
        return $this->container->application()->createOrganizer(new CreateOrganizer());
    }

    private function aUser(): UserId
    {
        return $this->container->application()->createUser(new CreateUser('A user'));
    }
}
