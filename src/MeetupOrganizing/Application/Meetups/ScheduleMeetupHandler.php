<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Meetups;

use MeetupOrganizing\Domain\Model\Meetup\CouldNotScheduleMeetup;
use MeetupOrganizing\Domain\Model\Meetup\Meetup;
use MeetupOrganizing\Domain\Model\Meetup\MeetupId;
use MeetupOrganizing\Domain\Model\Meetup\MeetupRepository;
use MeetupOrganizing\Domain\Model\User\UserRepository;

final class ScheduleMeetupHandler
{
    private MeetupRepository $meetupRepository;
    private UserRepository $userRepository;

    public function __construct(MeetupRepository $meetupRepository, UserRepository $userRepository)
    {
        $this->meetupRepository = $meetupRepository;
        $this->userRepository = $userRepository;
    }

    public function handle(ScheduleMeetup $command): MeetupId
    {
        $organizer = $this->userRepository->getById($command->organizerId());
        if (!$organizer->isOrganizer()) {
            throw CouldNotScheduleMeetup::becauseTheUserIsNoOrganizer();
        }

        $meetup = Meetup::schedule(
            $this->meetupRepository->nextIdentity(),
            $organizer->userId(),
            $command->countryCode(),
            $command->title(),
            $command->description(),
            $command->scheduledDate()
        );

        $this->meetupRepository->save($meetup);

        return $meetup->meetupId();
    }
}
