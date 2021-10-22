<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Meetups;

use MeetupOrganizing\Domain\Model\Meetup\MeetupRepository;
use MeetupOrganizing\Domain\Model\User\UserRepository;

final class RsvpToMeetupHandler
{
    private UserRepository $userRepository;
    private MeetupRepository $meetupRepository;

    public function __construct(UserRepository $userRepository, MeetupRepository $meetupRepository)
    {
        $this->userRepository = $userRepository;
        $this->meetupRepository = $meetupRepository;
    }

    public function handle(RsvpToMeetup $command): void
    {
    }
}
