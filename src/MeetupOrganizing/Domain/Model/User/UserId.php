<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\User;

use Common\DomainModel\AggregateId;

final class UserId
{
    use AggregateId;

    public function asString(): string
    {
        return $this->__toString();
    }
}
