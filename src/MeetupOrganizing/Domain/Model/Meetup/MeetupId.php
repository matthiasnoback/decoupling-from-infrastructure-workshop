<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\Meetup;

use Common\DomainModel\AggregateId;

final class MeetupId
{
    use AggregateId;

    public function asString(): string
    {
        return $this->__toString();
    }
}
