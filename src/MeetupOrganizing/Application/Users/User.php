<?php
declare(strict_types=1);

namespace MeetupOrganizing\Application\Users;

use MeetupOrganizing\Domain\Model\Common\Mapping;
use MeetupOrganizing\Domain\Model\User\UserId;

final class User
{
    use Mapping;

    private UserId $userId;
    private string $username;

    public function __construct(UserId $userId, string $username)
    {
        $this->userId = $userId;
        $this->username = $username;
    }

    public static function create(UserId $userId, string $username): self
    {
        return new self($userId, $username);
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function username(): string
    {
        return $this->username;
    }

    /**
     * @param array<string,string|null> $userRecord
     */
    public static function fromDatabaseRecord(array $userRecord): self
    {
        return new self(
            UserId::fromString(self::getString($userRecord, 'userId')),
            self::getString($userRecord, 'username')
        );
    }
}
