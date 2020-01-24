<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Training;

use DevPro\Domain\Model\User\UserId;

final class AttendeeWasRegistered
{
    /**
     * @var TrainingId
     */
    private $trainingId;

    /**
     * @var UserId
     */
    private $userId;

    public function __construct(
        TrainingId $trainingId,
        UserId $userId
    ) {
        $this->trainingId = $trainingId;
        $this->userId = $userId;
    }

    public function trainingId(): TrainingId
    {
        return $this->trainingId;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function __toString(): string
    {
        return 'trainingId: ' . $this->trainingId->asString() . ', userId: ' . $this->userId->asString();
    }
}
