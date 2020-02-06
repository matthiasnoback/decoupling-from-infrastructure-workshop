<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Training;

use Assert\Assert;
use DevPro\Domain\Model\Common\EventRecordingCapabilities;
use DevPro\Domain\Model\User\UserId;

final class Training
{
    use EventRecordingCapabilities;

    /**
     * @var TrainingId
     */
    private $trainingId;

    /**
     * @var UserId
     */
    private $organizerId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var ScheduledDate
     */
    private $scheduledDate;

    /**
     * @var array & UserId[]
     */
    private $attendees = [];

    private function __construct(
        TrainingId $trainingId,
        UserId $organizerId,
        string $title,
        ScheduledDate $scheduledDate
    ) {
        Assert::that($title)->notEmpty('Title should not be empty');

        $this->trainingId = $trainingId;
        $this->organizerId = $organizerId;
        $this->title = $title;
        $this->scheduledDate = $scheduledDate;
    }

    public static function schedule(
        TrainingId $trainingId,
        UserId $organizerId,
        string $title,
        ScheduledDate $scheduledDate
    ): self {
        $training = new self(
            $trainingId,
            $organizerId,
            $title,
            $scheduledDate
        );

        $training->recordThat(new TrainingWasScheduled($trainingId, $title, $scheduledDate));

        return $training;
    }

    public function trainingId(): TrainingId
    {
        return $this->trainingId;
    }

    public function registerAttendee(UserId $userId): void
    {
        $this->attendees[] = $userId;

        $this->recordThat(new AttendeeWasRegistered($this->trainingId, $userId));
    }
}
