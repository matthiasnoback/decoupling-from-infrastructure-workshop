<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Training;

use Assert\Assert;
use DateTimeImmutable;
use DevPro\Domain\Model\Common\EventRecordingCapabilities;
use DevPro\Domain\Model\User\UserId;

final class Training
{
    use EventRecordingCapabilities;

    private TrainingId $trainingId;
    private UserId $organizerId;
    private string $title;
    private DateTimeImmutable $scheduledDate;

    private function __construct(
        TrainingId $trainingId,
        UserId $organizerId,
        string $title,
        DateTimeImmutable $scheduledDate
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
        DateTimeImmutable $scheduledDate
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
}
