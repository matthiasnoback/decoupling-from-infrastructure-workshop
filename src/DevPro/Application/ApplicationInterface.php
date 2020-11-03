<?php
declare(strict_types=1);

namespace DevPro\Application;

use DevPro\Domain\Model\User\UserId;

interface ApplicationInterface
{
    public function createUser(string $username): UserId;

    public function createOrganizer(): UserId;
}
