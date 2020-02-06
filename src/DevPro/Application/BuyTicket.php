<?php
declare(strict_types=1);

namespace DevPro\Application;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Domain\Model\Ticket\Ticket;
use DevPro\Domain\Model\Ticket\TicketRepository;
use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Domain\Model\User\UserId;
use DevPro\Domain\Model\User\UserRepository;

final class BuyTicket
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var TrainingRepository
     */
    private $trainingRepository;

    /**
     * @var TicketRepository
     */
    private $ticketRepository;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(
        UserRepository $userRepository,
        TrainingRepository $trainingRepository,
        TicketRepository $ticketRepository,
        EventDispatcher $eventDispatcher
    ) {
        $this->userRepository = $userRepository;
        $this->trainingRepository = $trainingRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->ticketRepository = $ticketRepository;
    }

    public function buyTicket(string $userId, string $trainingId): void
    {
        $ticketId = $this->ticketRepository->nextIdentity();
        $user = $this->userRepository->getById(UserId::fromString($userId));
        $training = $this->trainingRepository->getById(TrainingId::fromString($trainingId));

        $ticket = Ticket::buyForTraining($ticketId, $user->userId(), $training->trainingId());

        $this->ticketRepository->save($ticket);

        $this->eventDispatcher->dispatchAll($ticket->releaseEvents());
    }
}
