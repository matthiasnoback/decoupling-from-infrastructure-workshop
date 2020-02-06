<?php
declare(strict_types=1);

namespace DevPro\Application;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Domain\Model\Ticket\TicketWasBoughtForTraining;
use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Domain\Model\User\UserId;

final class RegisterAttendee
{
    /**
     * @var TrainingRepository
     */
    private $trainingRepository;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(TrainingRepository $trainingRepository, EventDispatcher $eventDispatcher)
    {
        $this->trainingRepository = $trainingRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function whenTicketWasBoughtForTraining(TicketWasBoughtForTraining $event): void
    {
        $this->registerAttendee($event->trainingId(), $event->userId());
    }

    public function registerAttendee(TrainingId $trainingId, UserId $userId): void
    {
        $training = $this->trainingRepository->getById($trainingId);

        $training->registerAttendee($userId);

        $this->trainingRepository->save($training);

        $this->eventDispatcher->dispatchAll($training->releaseEvents());
    }
}
