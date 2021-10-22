<?php
declare(strict_types=1);

namespace Test\UseCases;

use MeetupOrganizing\Application\Meetups\ScheduleMeetup;
use MeetupOrganizing\Application\Meetups\RsvpToMeetup;
use MeetupOrganizing\Application\Users\CreateUser;
use MeetupOrganizing\Domain\Model\Meetup\MeetupId;
use MeetupOrganizing\Domain\Model\User\User;

final class RsvpToAMeetupTest extends AbstractUseCaseTestCase
{
    /**
     * @test
     */
    public function a_user_rsvps_to_a_meetup(): void
    {
        /*
         * Feature: RSVPs
         *
         *     An organizer wants to be able to estimate how many people will attend. This helps them set up the
         *     required number of chairs, order a suitable number of pizzas, etc.
         *     An attendee wants to be able to take a look at the list of attendees to find out if they know any of
         *     them. Based on the list they can, for example, decide to go to the meetup or stay home if they don't feel
         *     at ease in an unfamiliar crowd.
         */

        $user = $this->aUser();
        $meetupId = $this->aMeetup();

        //When a user RSVPs to a meetup
        $this->container->application()->rsvpToMeetup(
            new RsvpToMeetup($meetupId, $user->userId())
        );

        //Then their name should be on the list of attendees for this meetup
        $meetupDetails = $this->container->application()->meetupDetails($meetupId->asString());
        self::assertContains($user->username(), $meetupDetails->attendeeNames());
    }

    /**
     * @test
     */
    public function a_user_has_already_rsvped_to_the_meetup(): void
    {
        $this->markTestIncomplete();

        //Given a user has already RSVPed to a meetup
        //When they RSVP to the same meetup again
        //Then they should still be registered once as an attendee
    }

    /**
     * @test
     */
    public function the_maximum_number_of_attendees_is_reached(): void
    {
        $this->markTestIncomplete();
        //Given the organizer has scheduled a meetup with a maximum of 5 attendees
        //And so far 4 attendees have RSVPed to this meetup
        //When a user RSVPs to this meetup
        //Then the meetup should still show up on the list of upcoming meetups
        //But it will be impossible for another user to RSVP to this meetup
    }

    /**
     * @test
     */
    public function a_user_revokes_their_rsvp(): void
    {
        $this->markTestIncomplete();

        //Given the organizer has scheduled a meetup with a maximum of 5 attendees
        //And so far 5 attendees have RSVPed to this meetup
        //When one attendee revokes their RSVP
        //Then another user can RSVP to this meetup
    }

    /**
     * @test
     */
    public function the_meetup_is_in_the_past(): void
    {
        $this->markTestIncomplete();

        //Given a meetup has been scheduled for "2020-01-15"
        //When it's "2020-01-15"
        //Then it's impossible to RSVP to this meetup
    }

    private function aMeetup(): MeetupId
    {
        return $this->container->application()->scheduleMeetup(
            new ScheduleMeetup(
                $this->anOrganizer()->userId()->asString(),
                $this->aCountryCode(),
                $this->aTitle(),
                $this->aDescription(),
                $this->aDateAndTime()
            )
        );
    }

    private function anOrganizer(): User
    {
        return $this->aUser('Organizer', true);
    }

    private function aUser(string $username = 'User', bool $isOrganizer = false): User
    {
        $userId = $this->container->application()->createUser(new CreateUser($username, $isOrganizer));

        return $this->container->userRepository()->getById($userId);
    }

    private function aCountryCode(): string
    {
        return 'NL';
    }

    private function aTitle(): string
    {
        return 'Decoupling from infrastructure';
    }

    private function aDescription(): string
    {
        return 'Should be interesting';
    }

    private function aDateAndTime(): string
    {
        return '2021-01-01T20:00';
    }
}
