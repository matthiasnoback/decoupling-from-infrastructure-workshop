<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Users;

final class CreateUser
{
    private string $username;
    private bool $isOrganizer;

    public function __construct(string $username, bool $isOrganizer)
    {
        $this->username = $username;
        $this->isOrganizer = $isOrganizer;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function isOrganizer(): bool
    {
        return $this->isOrganizer;
    }
}
