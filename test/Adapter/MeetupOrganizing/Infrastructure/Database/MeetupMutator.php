<?php
declare(strict_types=1);

namespace Test\Adapter\MeetupOrganizing\Infrastructure\Database;

use MeetupOrganizing\Domain\Model\Meetup\Meetup;

interface MeetupMutator
{
    public function mutate(Meetup $meetup): Meetup;
}
