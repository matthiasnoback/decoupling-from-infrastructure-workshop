<?php
declare(strict_types=1);

namespace Test\Adapter\MeetupOrganizing\Infrastructure;

use MeetupOrganizing\Application\ApplicationInterface;
use MeetupOrganizing\Application\Meetups\MeetupDetails;
use MeetupOrganizing\Application\Meetups\ScheduleMeetup;
use MeetupOrganizing\Application\Meetups\UpcomingMeetup;
use MeetupOrganizing\Application\No;
use MeetupOrganizing\Application\RsvpToMeetup;
use MeetupOrganizing\Application\Users\CreateUser;
use MeetupOrganizing\Domain\Model\Meetup\MeetupId;
use MeetupOrganizing\Domain\Model\User\UserId;

final class ApplicationSpy implements ApplicationInterface
{
    const COMMAND_SENT_HEADER = 'X-Command-Sent';

    public function __construct()
    {
    }

    public function createUser(CreateUser $command): UserId
    {
        $this->recordThatCommandWasSent($command);

        return UserId::fromString('5e4e7be5-b46d-4b25-bcb7-affa8ec37655');
    }

    public function scheduleMeetup(ScheduleMeetup $command): MeetupId
    {
        $this->recordThatCommandWasSent($command);

        return MeetupId::fromString('8db7f6ed-adc0-46f6-8f0c-b97c0f3821e3');
    }

    public function rsvpToMeetup(RsvpToMeetup $command): void
    {
        throw new No();
    }

    public function upcomingMeetups(): array
    {
        return [
            new UpcomingMeetup(
                '8db7f6ed-adc0-46f6-8f0c-b97c0f3821e3',
                '2020-01-24T20:00',
                'Decoupling from infrastructure'
            )
        ];
    }

    public function meetupDetails(string $meetupId): MeetupDetails
    {
        return new MeetupDetails(
            '8db7f6ed-adc0-46f6-8f0c-b97c0f3821e3',
            '2020-01-24T20:00',
            'Decoupling from infrastructure',
            'Should be interesting'
        );
    }

    private function recordThatCommandWasSent(object $command): void
    {
        header(self::COMMAND_SENT_HEADER . ': ' . base64_encode(serialize($command)));
    }
}
