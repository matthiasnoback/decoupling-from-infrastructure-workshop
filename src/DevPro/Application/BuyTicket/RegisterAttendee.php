<?php
declare(strict_types=1);

namespace DevPro\Application\BuyTicket;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Domain\Model\Ticket\TicketWasBoughtForTraining;
use DevPro\Domain\Model\Training\TrainingRepository;

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
        $training = $this->trainingRepository->getById($event->trainingId());

        $training->registerAttendee($event->userId());

        $this->trainingRepository->save($training);

        $this->eventDispatcher->dispatchAll($training->releaseEvents());
    }
}
