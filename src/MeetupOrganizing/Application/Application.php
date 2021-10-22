<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use MeetupOrganizing\Application\Meetups\MeetupDetails;
use MeetupOrganizing\Application\Meetups\MeetupDetailsRepository;
use MeetupOrganizing\Application\Meetups\ScheduleMeetup;
use MeetupOrganizing\Application\Meetups\ScheduleMeetupHandler;
use MeetupOrganizing\Application\Meetups\UpcomingMeetupRepository;
use MeetupOrganizing\Application\Users\CreateUser;
use MeetupOrganizing\Application\Users\CreateUserHandler;
use MeetupOrganizing\Domain\Model\Meetup\MeetupId;
use MeetupOrganizing\Domain\Model\User\UserId;

final class Application implements ApplicationInterface
{
    private CreateUserHandler $createUserHandler;
    private ScheduleMeetupHandler $scheduleMeetupHandler;
    private UpcomingMeetupRepository $upcomingMeetupRepository;
    private MeetupDetailsRepository $meetupDetailsRepository;

    public function __construct(
        CreateUserHandler $createUserHandler,
        ScheduleMeetupHandler $scheduleMeetupHandler,
        UpcomingMeetupRepository $upcomingMeetupRepository,
        MeetupDetailsRepository $meetupDetailsRepository
    ) {
        $this->createUserHandler = $createUserHandler;
        $this->scheduleMeetupHandler = $scheduleMeetupHandler;
        $this->upcomingMeetupRepository = $upcomingMeetupRepository;
        $this->meetupDetailsRepository = $meetupDetailsRepository;
    }

    public function createUser(CreateUser $command): UserId
    {
        return $this->createUserHandler->handle($command);
    }

    public function scheduleMeetup(ScheduleMeetup $command): MeetupId
    {
        return $this->scheduleMeetupHandler->handle($command);
    }

    public function upcomingMeetups(): array
    {
        return $this->upcomingMeetupRepository->findAll();
    }

    public function meetupDetails(string $meetupId): MeetupDetails
    {
        return $this->meetupDetailsRepository->getMeetupDetails(MeetupId::fromString($meetupId));
    }

    public function rsvpToMeetup(RsvpToMeetup $command): void
    {
        throw new No('@TODO: call a handler');
    }
}
