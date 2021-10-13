<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\Rsvp;

use Common\DomainModel\AggregateId;

final class RsvpId
{
    use AggregateId;

    public function asString(): string
    {
        return $this->__toString();
    }
}
