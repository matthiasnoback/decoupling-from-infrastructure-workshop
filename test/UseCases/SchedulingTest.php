<?php
declare(strict_types=1);

namespace Test\UseCases;

use MeetupOrganizing\Application\Users\CreateOrganizer;
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

        // Then it shows up on the list of upcoming meetups
        $this->markTestIncomplete('TODO');
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
        return $this->container->application()->createOrganizer(new CreateOrganizer());
    }
}
