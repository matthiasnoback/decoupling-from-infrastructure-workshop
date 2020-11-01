<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\User;

use Assert\Assert;
use DevPro\Domain\Model\Common\EventRecordingCapabilities;

final class User
{
    use EventRecordingCapabilities;

    private UserId $userId;
    private string $name;
    private bool $isOrganizer;

    private function __construct()
    {
    }

    public static function createNormalUser(UserId $userId, string $name): self
    {
        $instance = new self();

        Assert::that($name)->notEmpty('The name of a user should not be empty');

        $instance->userId = $userId;
        $instance->name = $name;
        $instance->isOrganizer = false;

        return $instance;
    }

    public static function createOrganizer(UserId $userId): self
    {
        $instance = new self();

        $instance->userId = $userId;
        $instance->name = 'Organizer';
        $instance->isOrganizer = true;

        return $instance;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function getDatabaseRecordData(): array
    {
        return [
            'id' => $this->userId->asString(),
            'name' => $this->name,
            'isOrganizer' => $this->isOrganizer
        ];
    }

    public static function fromDatabaseRecord(array $data): self
    {
        $instance = new self();

        $instance->userId = UserId::fromString($data['id']);
        $instance->name = $data['name'];
        $instance->isOrganizer = (bool)$data['isOrganizer'];

        return $instance;
    }
}
