<?php
declare(strict_types=1);

namespace DevPro\Application\ScheduleTraining;

use Assert\Assert;
use DateTimeImmutable;
use DevPro\Domain\Model\Training\Training;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Domain\Model\User\UserId;

final class ScheduleTraining
{
    /**
     * @var TrainingRepository
     */
    private $trainingRepository;

    public function __construct(TrainingRepository $trainingRepository)
    {
        $this->trainingRepository = $trainingRepository;
    }

    public function schedule(string $organizerId, string $title, string $scheduledDate): void
    {
        $scheduledDate = DateTimeImmutable::createFromFormat('d-m-Y', $scheduledDate);
        Assert::that($scheduledDate)->isInstanceOf(DateTimeImmutable::class);

        $training = Training::schedule(
            $this->trainingRepository->nextIdentity(),
            UserId::fromString($organizerId),
            $title,
            $scheduledDate
        );

        $this->trainingRepository->save($training);
    }
}
