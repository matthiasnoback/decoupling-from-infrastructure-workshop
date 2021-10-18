<?php
declare(strict_types=1);

namespace Test\Adapter\MeetupOrganizing\Infrastructure;

use BadMethodCallException;
use MeetupOrganizing\Application\ApplicationInterface;
use MeetupOrganizing\Application\Meetups\ScheduleMeetup;
use MeetupOrganizing\Application\Users\CreateOrganizer;
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

    public function createOrganizer(CreateOrganizer $command): UserId
    {
        throw new BadMethodCallException('Not implemented');
    }

    public function scheduleMeetup(ScheduleMeetup $command): MeetupId
    {
        $this->recordThatCommandWasSent($command);

        return MeetupId::fromString('8db7f6ed-adc0-46f6-8f0c-b97c0f3821e3');
    }

    public function upcomingMeetups(): array
    {
        return [];
    }

    private function recordThatCommandWasSent(object $command): void
    {
        header(self::COMMAND_SENT_HEADER . ': ' . base64_encode(serialize($command)));
    }
}
