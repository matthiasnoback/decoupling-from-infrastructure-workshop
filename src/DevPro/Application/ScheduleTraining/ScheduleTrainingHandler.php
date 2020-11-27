<?php
declare(strict_types=1);

namespace DevPro\Application\ScheduleTraining;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Application\Holidays\NationalHolidays;
use DevPro\Domain\Model\Training\Training;
use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\Training\TrainingRepository;
use RuntimeException;

final class ScheduleTrainingHandler
{
    private TrainingRepository $trainingRepository;
    private NationalHolidays $nationalHolidays;
    private EventDispatcher $eventDispatcher;

    public function __construct(
        TrainingRepository $trainingRepository,
        NationalHolidays $nationalHolidays,
        EventDispatcher $eventDispatcher
    ) {
        $this->trainingRepository = $trainingRepository;
        $this->nationalHolidays = $nationalHolidays;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(ScheduleTraining $command): TrainingId
    {
        if ($this->nationalHolidays->isNationalHolidayInCountry(
            $command->scheduledDate(),
            $command->country()
        )) {
            throw new RuntimeException('The date of the training is a national holiday');
        }

        $training = Training::schedule(
            $this->trainingRepository->nextIdentity(),
            $command->organizerId(),
            $command->country(),
            $command->title(),
            $command->scheduledDate()
        );

        $this->eventDispatcher->dispatchAll($training->releaseEvents());

        return $training->trainingId();
    }
}
