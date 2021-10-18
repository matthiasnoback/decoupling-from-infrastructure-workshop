<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use MeetupOrganizing\Application\Meetups\ScheduleMeetup;
use MeetupOrganizing\Application\Meetups\ScheduleMeetupHandler;
use MeetupOrganizing\Application\Meetups\UpcomingMeetupRepository;
use MeetupOrganizing\Application\Users\CreateOrganizer;
use MeetupOrganizing\Application\Users\CreateOrganizerHandler;
use MeetupOrganizing\Application\Users\CreateUser;
use MeetupOrganizing\Application\Users\CreateUserHandler;
use MeetupOrganizing\Domain\Model\Meetup\MeetupId;
use MeetupOrganizing\Domain\Model\User\UserId;

final class Application implements ApplicationInterface
{
    private CreateUserHandler $createUserHandler;
    private CreateOrganizerHandler $createOrganizerHandler;
    private ScheduleMeetupHandler $scheduleMeetupHandler;
    private UpcomingMeetupRepository $upcomingMeetupRepository;

    public function __construct(
        CreateUserHandler $createUserHandler,
        CreateOrganizerHandler $createOrganizerHandler,
        ScheduleMeetupHandler $scheduleMeetupHandler,
        UpcomingMeetupRepository $upcomingMeetupRepository
    ) {
        $this->createUserHandler = $createUserHandler;
        $this->createOrganizerHandler = $createOrganizerHandler;
        $this->scheduleMeetupHandler = $scheduleMeetupHandler;
        $this->upcomingMeetupRepository = $upcomingMeetupRepository;
    }

    public function createUser(CreateUser $command): UserId
    {
        return $this->createUserHandler->handle($command);
    }

    public function createOrganizer(CreateOrganizer $command): UserId
    {
        return $this->createOrganizerHandler->handle($command);
    }

    public function scheduleMeetup(ScheduleMeetup $command): MeetupId
    {
        return $this->scheduleMeetupHandler->handle($command);
    }

    public function upcomingMeetups(): array
    {
        return $this->upcomingMeetupRepository->findAll();
    }
}
