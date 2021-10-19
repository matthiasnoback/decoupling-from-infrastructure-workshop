<?php
declare(strict_types=1);

namespace Test\UseCases;

use MeetupOrganizing\Application\Meetups\ScheduleMeetup;
use MeetupOrganizing\Application\Meetups\UpcomingMeetup;
use MeetupOrganizing\Application\Users\CreateUser;
use MeetupOrganizing\Domain\Model\User\UserId;

final class SchedulingMeetupsTest extends AbstractUseCaseTestCase
{
    protected function setUp(): void
    {
        $this->container->setCurrentDate('2020-01-01');
    }

    /**
     * @test
     */
    public function an_organizer_schedules_a_meetup(): void
    {
        /*
         * Feature: Scheduling meetups
         *
         *     Organizers want to schedule meetups, so they can host members of the community who want to share their
         *     experience with others. Potential attendees should be able to look at upcoming meetups to get a quick
         *     overview of which meetups they can attend in the near future.
         */

        // When the organizer schedules a new meetup called "Decoupling from infrastructure" for "2020-01-24 20:00"
        $title = 'Decoupling from infrastructure';
        $description = 'Should be interesting';
        $scheduledMeetupId = $this->container->application()->scheduleMeetup(
            new ScheduleMeetup(
                $this->theOrganizer()->asString(),
                $this->aCountryCode(),
                $title,
                $description,
                '2020-01-24T20:00'
            )
        );

        // Then it shows up on the list of upcoming meetups
        $allTitles = array_map(
            fn (UpcomingMeetup $upcomingMeetup) => $upcomingMeetup->title(),
            $this->container->application()->upcomingMeetups()
        );
        self::assertContains($title, $allTitles);

        // And details like the description are available to the user when they are interested
        $meetupDetails = $this->container->application()->meetupDetails($scheduledMeetupId->asString());
        self::assertEquals($description, $meetupDetails->description());
    }

    /**
     * @test
     */
    public function the_day_of_the_meetup_is_in_the_past(): void
    {
        $this->markTestIncomplete('TODO');

        //Given a meetup has been scheduled for "2020-01-24T20:00"
        //When it's "2020-01-25"
        //Then it does not show up on the list of upcoming meetups anymore
    }

    /**
     * @test
     */
    public function the_day_of_the_meetup_is_a_national_holiday(): void
    {
        // Given "2020-12-25" is a national holiday in "NL"

        // When the organizer tries to schedule a meetup on this date in this country

        // Then the meetup won't be scheduled because the date of the meetup is a national holiday

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
