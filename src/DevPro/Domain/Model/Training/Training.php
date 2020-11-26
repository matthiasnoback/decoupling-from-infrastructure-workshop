<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Training;

use Assert\Assert;
use DevPro\Domain\Model\Common\Country;
use DevPro\Domain\Model\Common\EventRecordingCapabilities;
use DevPro\Domain\Model\User\UserId;

final class Training
{
    use EventRecordingCapabilities;

    private TrainingId $trainingId;
    private UserId $organizerId;
    private string $title;
    private ScheduledDate $scheduledDate;
    private Country $country;

    private function __construct()
    {
    }

    public static function schedule(
        TrainingId $trainingId,
        UserId $organizerId,
        Country $country,
        string $title,
        ScheduledDate $scheduledDate
    ): self {
        Assert::that($title)->notEmpty('Title should not be empty');

        $training = new self();

        $training->trainingId = $trainingId;
        $training->organizerId = $organizerId;
        $training->country = $country;
        $training->title = $title;
        $training->scheduledDate = $scheduledDate;

        $training->recordThat(
            new TrainingWasScheduled($trainingId, $organizerId, $country, $title, $scheduledDate)
        );

        return $training;
    }

    public function trainingId(): TrainingId
    {
        return $this->trainingId;
    }
}
