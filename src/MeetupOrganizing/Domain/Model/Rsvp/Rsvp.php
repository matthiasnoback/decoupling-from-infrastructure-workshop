<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\Rsvp;

use MeetupOrganizing\Domain\Model\Common\EventRecordingCapabilities;
use MeetupOrganizing\Domain\Model\Meetup\MeetupId;
use MeetupOrganizing\Domain\Model\User\UserId;

final class Rsvp
{
    use EventRecordingCapabilities;

    private RsvpId $rsvpId;
    private UserId $userId;
    private MeetupId $meetupId;

    private function __construct(
        RsvpId $rsvpId,
        UserId $userId,
        MeetupId $meetupId
    ) {
        $this->rsvpId = $rsvpId;
        $this->userId = $userId;
        $this->meetupId = $meetupId;
    }

    public static function rsvpToMeetup(
        RsvpId $rsvpId,
        UserId $userId,
        MeetupId $meetupId
    ): self {
        return new self(
            $rsvpId,
            $userId,
            $meetupId
        );
    }

    public function rsvpId(): RsvpId
    {
        return $this->rsvpId;
    }
}
