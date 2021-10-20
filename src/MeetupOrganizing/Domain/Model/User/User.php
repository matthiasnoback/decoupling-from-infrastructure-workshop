<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\User;

use Assert\Assert;
use MeetupOrganizing\Domain\Model\Common\EventRecordingCapabilities;
use MeetupOrganizing\Infrastructure\Database\SchemaManager;

final class User
{
    use EventRecordingCapabilities;

    private UserId $userId;
    private string $username;
    private bool $isOrganizer;

    private function __construct()
    {
    }

    public static function create(UserId $userId, string $username, bool $isOrganizer): self
    {
        $instance = new self();

        Assert::that($username)->notEmpty('The name of a user should not be empty');

        $instance->userId = $userId;
        $instance->username = $username;
        $instance->isOrganizer = $isOrganizer;

        return $instance;
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
     * @return array<string,bool|string>
     *
     * @see SchemaManager::addUsersTable()
     */
    public function getDatabaseRecordData(): array
    {
        return [
            'userId' => $this->userId->asString(),
            'username' => $this->username,
            'isOrganizer' => $this->isOrganizer
        ];
    }

    /**
     * @see SchemaManager::addUsersTable()
     */
    public static function fromDatabaseRecord(array $data): self
    {
        $instance = new self();

        $instance->userId = UserId::fromString($data['userId']);
        $instance->username = $data['username'];
        $instance->isOrganizer = (bool)$data['isOrganizer'];

        return $instance;
    }

    public function isOrganizer(): bool
    {
        return $this->isOrganizer;
    }
}
