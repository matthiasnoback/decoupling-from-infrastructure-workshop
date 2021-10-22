<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use MeetupOrganizing\Domain\Model\Meetup\MeetupId;
use MeetupOrganizing\Domain\Model\User\UserId;

final class RsvpToMeetup
{
    private MeetupId $meetupId;
    private UserId $attendeeId;

    public function __construct(MeetupId $meetupId, UserId $attendeeId)
    {
        $this->meetupId = $meetupId;
        $this->attendeeId = $attendeeId;
    }

    public function meetupId(): MeetupId
    {
        return $this->meetupId;
    }

    public function attendeeId(): UserId
    {
        return $this->attendeeId;
    }
}
