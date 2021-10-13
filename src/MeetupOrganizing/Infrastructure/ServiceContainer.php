<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure;

use MeetupOrganizing\Application\ApplicationInterface;

interface ServiceContainer
{
    public function application(): ApplicationInterface;
}
