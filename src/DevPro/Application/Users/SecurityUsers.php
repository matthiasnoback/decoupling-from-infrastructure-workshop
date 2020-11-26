<?php
declare(strict_types=1);

namespace DevPro\Application\Users;

interface SecurityUsers
{
    public function getByUsername(string $username): SecurityUser;
}
