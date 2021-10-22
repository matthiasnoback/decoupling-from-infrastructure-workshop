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
        $user = $this->userRepository->getById($command->attendeeId());

        $meetup = $this->meetupRepository->getById($command->meetupId());
        $meetup = $meetup->withRsvp($user->userId());

        $this->meetupRepository->save($meetup);
    }
}
