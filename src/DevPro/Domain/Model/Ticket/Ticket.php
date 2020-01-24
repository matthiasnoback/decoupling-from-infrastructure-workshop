<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Ticket;

use DevPro\Domain\Model\Common\EventRecordingCapabilities;
use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\User\UserId;

final class Ticket
{
    use EventRecordingCapabilities;

    /**
     * @var TicketId
     */
    private $ticketId;

    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var TrainingId
     */
    private $trainingId;

    private function __construct(
        TicketId $ticketId,
        UserId $userId,
        TrainingId $trainingId
    ) {
        $this->ticketId = $ticketId;
        $this->userId = $userId;
        $this->trainingId = $trainingId;
    }

    public static function buyForTraining(
        TicketId $ticketId,
        UserId $userId,
        TrainingId $trainingId
    ): self {
        $ticket = new self(
            $ticketId,
            $userId,
            $trainingId
        );
        $ticket->recordThat(new TicketWasBoughtForTraining($userId, $trainingId));

        return $ticket;
    }

    public function ticketId(): TicketId
    {
        return $this->ticketId;
    }
}
