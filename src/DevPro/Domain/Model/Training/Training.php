<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Training;

use Assert\Assert;
use DateTimeImmutable;
use DevPro\Domain\Model\Common\Country;
use DevPro\Domain\Model\Common\EventRecordingCapabilities;
use DevPro\Domain\Model\User\UserId;

final class Training
{
    use EventRecordingCapabilities;

    private TrainingId $trainingId;
    private UserId $organizerId;
    private string $title;
    private DateTimeImmutable $scheduledDate;
    private Country $country;

    private function __construct()
    {
    }

    public static function schedule(
        TrainingId $trainingId,
        UserId $organizerId,
        Country $country,
        string $title,
        DateTimeImmutable $scheduledDate
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

    public static function fromDatabaseRecord(array $record): self
    {
        $training = new self();

        $training->trainingId = TrainingId::fromString($record['id']);
        $training->organizerId = UserId::fromString($record['organizerId']);
        $training->country = Country::fromString($record['country']);
        $training->title = $record['title'];
        $scheduledDate = DateTimeImmutable::createFromFormat('Y-m-d', $record['scheduledDate']);
        Assert::that($scheduledDate)->isInstanceOf(DateTimeImmutable::class);
        $training->scheduledDate = $scheduledDate;

        return $training;
    }

    public function trainingId(): TrainingId
    {
        return $this->trainingId;
    }

    public function getDatabaseRecordData(): array
    {
        return [
            'id' => $this->trainingId->asString(),
            'organizerId' => $this->organizerId->asString(),
            'country' => $this->country->asString(),
            'title' => $this->title,
            'scheduledDate' => $this->scheduledDate->format('Y-m-d')
        ];
    }
}
