<?php
declare(strict_types=1);

namespace Test\UseCases;

use MeetupOrganizing\Application\Meetups\ScheduleMeetup;
use MeetupOrganizing\Application\Meetups\UpcomingMeetup;
use MeetupOrganizing\Application\Users\CreateUser;
use MeetupOrganizing\Domain\Model\User\UserId;

final class SchedulingTest extends AbstractUseCaseTestCase
{
    protected function setUp(): void
    {
        $this->container->setCurrentDate('2020-01-01');
    }

    /**
     * @test
     */
    public function aScheduledMeetupShowsUpInUpcomingMeetups(): void
    {
        // When the organizer schedules a new meetup called "Decoupling from infrastructure" for "2020-01-24 20:00"
        $title = 'Decoupling from infrastructure';
        $this->container->application()->scheduleMeetup(
            new ScheduleMeetup(
                $this->theOrganizer()->asString(),
                $this->aCountryCode(),
                $title,
                '2020-01-24T20:00'
            )
        );

        // Then it shows up on the list of upcoming meetups
        $allTitles = array_map(
            fn (UpcomingMeetup $upcomingMeetup) => $upcomingMeetup->title(),
            $this->container->application()->upcomingMeetups()
        );
        self::assertContains($title, $allTitles);
    }

    /**
     * @test
     */
    public function theOrganizerTriesToScheduleAMeetupOnANationalHoliday(): void
    {
        // Given "2020-12-25" is a national holiday in "NL"

        // When the organizer tries to schedule a meetup on this date in this country

        // Then they see a message "The date of the meetup is a national holiday"

        $this->markTestIncomplete('TODO');
    }

    private function theOrganizer(): UserId
    {
        return $this->container->application()->createUser(new CreateUser('Organizer', true));
    }

    private function aCountryCode(): string
    {
        return 'NL';
    }
}
