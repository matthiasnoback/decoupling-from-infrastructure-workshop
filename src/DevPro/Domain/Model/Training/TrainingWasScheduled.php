<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Training;

use DateTimeImmutable;

final class TrainingWasScheduled
{
    /**
     * @var TrainingId
     */
    private $trainingId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var DateTimeImmutable
     */
    private $scheduledDate;

    public function __construct(
        TrainingId $trainingId,
        string $title,
        DateTimeImmutable $scheduledDate
    ) {
        $this->trainingId = $trainingId;
        $this->title = $title;
        $this->scheduledDate = $scheduledDate;
    }

    public function trainingId(): TrainingId
    {
        return $this->trainingId;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function scheduledDate(): DateTimeImmutable
    {
        return $this->scheduledDate;
    }
}
