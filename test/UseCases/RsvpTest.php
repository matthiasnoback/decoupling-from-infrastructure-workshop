<?php
declare(strict_types=1);

namespace Test\UseCases;

final class RsvpTest extends AbstractUseCaseTestCase
{
    /**
     * @test
     */
    public function attendees_can_register_themselves_for_sessions(): void
    {
        $this->markTestIncomplete();

        //Given the organizer has scheduled a meetup
        //When a user RSVPs to this meetup
        //Then they should be registered as an attendee
    }

    /**
     * @test
     */
    public function the_maximum_number_of_attendees_was_reached(): void
    {
        $this->markTestIncomplete();
        //Given the organizer has scheduled a meetup with a maximum of 5 attendees
        //And so far 4 attendees have registered themselves for this meetup
        //When a user RSVPs to this meetup
        //Then the meetup still shows up on the list of upcoming meetups
        //But it will be marked as Sold out
        //And it's impossible to RSVP to this meetup
    }

    /**
     * @test
     */
    public function the_end_of_sales_date_has_been_reached(): void
    {
        $this->markTestIncomplete();

        //Given a meetup has been scheduled for "2020-01-15"
        //When it's "2020-01-15"
        //Then it's impossible to RSVP to this meetup
    }

    /**
     * @test
     */
    public function it_is_the_day_of_the_meetup(): void
    {
        $this->markTestIncomplete();

        //Given a meetup has been scheduled for "2020-01-24"
        //When it's "2020-01-24"
        //Then it does not show up on the list of upcoming meetups anymore
    }

    /**
     * @test
     */
    public function an_rsvp_is_revoked(): void
    {
        $this->markTestIncomplete();

        //Given the organizer has scheduled a meetup with a maximum of 5 attendees
        //And so far 5 attendees have RSVPed to this meetup
        //When one attendee revokes their RSVP
        //Then another user can RSVP to this meetup
    }
}
