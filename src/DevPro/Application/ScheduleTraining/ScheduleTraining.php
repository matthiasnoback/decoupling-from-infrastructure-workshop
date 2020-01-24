<?php
declare(strict_types=1);

namespace DevPro\Application\ScheduleTraining;

use Assert\Assert;
use Common\EventDispatcher\EventDispatcher;
use DateTimeImmutable;
use DevPro\Domain\Model\Training\Training;
use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Domain\Model\User\UserId;

final class ScheduleTraining
{
    /**
     * @var TrainingRepository
     */
    private $trainingRepository;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(
        TrainingRepository $trainingRepository,
        EventDispatcher $eventDispatcher
    ) {
        $this->trainingRepository = $trainingRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function schedule(string $organizerId, string $title, string $scheduledDate): TrainingId
    {
        $scheduledDate = DateTimeImmutable::createFromFormat('d-m-Y', $scheduledDate);
        Assert::that($scheduledDate)->isInstanceOf(DateTimeImmutable::class);

        $trainingId = $this->trainingRepository->nextIdentity();

        $training = Training::schedule(
            $trainingId,
            UserId::fromString($organizerId),
            $title,
            $scheduledDate
        );

        $this->trainingRepository->save($training);

        $this->eventDispatcher->dispatchAll($training->releaseEvents());

        return $trainingId;
    }
}
