<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\Meetup;

use RuntimeException;

interface MeetupRepository
{
    public function save(Meetup $meetup): void;

    /**
     * @throws RuntimeException When the entity could not be found
     */
    public function getById(MeetupId $meetupId): Meetup;

    public function nextIdentity(): MeetupId;
}
