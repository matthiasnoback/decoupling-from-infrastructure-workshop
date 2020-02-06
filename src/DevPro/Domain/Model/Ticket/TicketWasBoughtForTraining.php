<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Ticket;

use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\User\UserId;

final class TicketWasBoughtForTraining
{
    /**
     * @var TicketId
     */
    private $ticketId;

    /**
     * @var TrainingId
     */
    private $trainingId;

    /**
     * @var UserId
     */
    private $userId;

    public function __construct(TicketId $ticketId, TrainingId $trainingId, UserId $userId)
    {
        $this->ticketId = $ticketId;
        $this->trainingId = $trainingId;
        $this->userId = $userId;
    }

    public function trainingId(): TrainingId
    {
        return $this->trainingId;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }
}
