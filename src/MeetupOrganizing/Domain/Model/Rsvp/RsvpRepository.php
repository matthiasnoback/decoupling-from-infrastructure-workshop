<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\Rsvp;

use RuntimeException;

interface RsvpRepository
{
    public function save(Rsvp $entity): void;

    /**
     * @throws RuntimeException When the entity could not be found
     */
    public function getById(RsvpId $id): Rsvp;

    public function nextIdentity(): RsvpId;
}
