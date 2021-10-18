<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Meetups;

interface UpcomingMeetupRepository
{
    /**
     * @return array<UpcomingMeetup>
     */
    public function findAll(): array;
}
