<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Meetups;

use MeetupOrganizing\Domain\Model\Meetup\MeetupId;

interface MeetupDetailsRepository
{
    public function getMeetupDetails(MeetupId $meetupId): MeetupDetails;
}
