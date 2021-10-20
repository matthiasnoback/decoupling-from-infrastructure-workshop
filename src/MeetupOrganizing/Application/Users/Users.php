<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Users;

use MeetupOrganizing\Domain\Model\User\CouldNotFindUser;
use MeetupOrganizing\Domain\Model\User\UserId;

interface Users
{
    /**
     * @throws CouldNotFindUser
     */
    public function getByUsername(string $username): User;

    /**
     * @throws CouldNotFindUser
     */
    public function getById(UserId $userId): User;
}
