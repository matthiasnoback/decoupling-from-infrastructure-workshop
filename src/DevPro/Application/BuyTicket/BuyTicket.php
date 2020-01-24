<?php
declare(strict_types=1);

namespace DevPro\Application\BuyTicket;

use DevPro\Domain\Model\Ticket\Ticket;
use DevPro\Domain\Model\Ticket\TicketRepository;
use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Domain\Model\User\UserId;
use DevPro\Domain\Model\User\UserRepository;

final class BuyTicket
{
    /**
     * @var TrainingRepository
     */
    private $trainingRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var TicketRepository
     */
    private $ticketRepository;

    public function __construct(
        TrainingRepository $trainingRepository,
        UserRepository $userRepository,
        TicketRepository $ticketRepository
    ) {
        $this->trainingRepository = $trainingRepository;
        $this->userRepository = $userRepository;
        $this->ticketRepository = $ticketRepository;
    }

    public function buyForTraining(string $trainingId, string $userId): void
    {
        $training = $this->trainingRepository->getById(TrainingId::fromString($trainingId));
        $user = $this->userRepository->getById(UserId::fromString($userId));

        $ticket = Ticket::buyForTraining(
            $this->ticketRepository->nextIdentity(),
            $user->userId(),
            $training->trainingId()
        );

        $this->ticketRepository->save($ticket);
    }
}
