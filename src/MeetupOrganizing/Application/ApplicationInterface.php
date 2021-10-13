<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application;

use MeetupOrganizing\Application\Users\CreateOrganizer;
use MeetupOrganizing\Application\Users\CreateUser;
use MeetupOrganizing\Domain\Model\User\UserId;

interface ApplicationInterface
{
    public function createUser(CreateUser $command): UserId;

    public function createOrganizer(CreateOrganizer $command): UserId;
}
