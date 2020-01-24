<?php
declare(strict_types=1);

namespace DevPro\Application\ListUpcomingEvents;

use DevPro\Domain\Model\Training\TrainingId;

final class UpcomingEvent
{
    /**
     * @var TrainingId
     */
    private $trainingId;

    /**
     * @var string
     */
    private $title;

    public function __construct(TrainingId $trainingId, string $title)
    {
        $this->trainingId = $trainingId;
        $this->title = $title;
    }

    public function trainingId(): string
    {
        return $this->trainingId->asString();
    }

    public function title(): string
    {
        return $this->title;
    }
}
