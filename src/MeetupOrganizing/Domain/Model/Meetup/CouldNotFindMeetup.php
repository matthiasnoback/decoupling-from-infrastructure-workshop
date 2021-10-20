<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\Meetup;

use RuntimeException;

final class CouldNotFindMeetup extends RuntimeException
{
    public static function withId(MeetupId $meetupId): self
    {
        return new self(sprintf('Could not find meetup with ID "%s"', $meetupId->asString()));
    }
}
