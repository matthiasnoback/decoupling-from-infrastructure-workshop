<?php
declare(strict_types=1);

namespace DevPro\Application\Users;

interface GetSecurityUser
{
    public function byUsername(string $username): SecurityUser;
}
