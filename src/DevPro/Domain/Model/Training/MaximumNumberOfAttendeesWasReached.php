<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Training;

final class MaximumNumberOfAttendeesWasReached
{
    /**
     * @var TrainingId
     */
    private $trainingId;

    public function __construct(TrainingId $trainingId)
    {
        $this->trainingId = $trainingId;
    }

    public function trainingId(): TrainingId
    {
        return $this->trainingId;
    }
}
