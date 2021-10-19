<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use MeetupOrganizing\Application\Meetups\MeetupDetails;
use MeetupOrganizing\Application\Meetups\ScheduleMeetup;
use MeetupOrganizing\Application\Meetups\UpcomingMeetup;
use MeetupOrganizing\Application\Users\CreateUser;
use MeetupOrganizing\Domain\Model\Meetup\MeetupId;
use MeetupOrganizing\Domain\Model\User\UserId;

interface ApplicationInterface
{
    public function createUser(CreateUser $command): UserId;

    public function scheduleMeetup(ScheduleMeetup $command): MeetupId;

    /**
     * @return array<UpcomingMeetup>
     */
    public function upcomingMeetups(): array;

    public function meetupDetails(string $meetupId): MeetupDetails;
}
