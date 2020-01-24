<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Ticket;

use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\User\UserId;

final class TicketWasBoughtForTraining
{
    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var TrainingId
     */
    private $trainingId;

    public function __construct(UserId $userId, TrainingId $trainingId)
    {
        $this->userId = $userId;
        $this->trainingId = $trainingId;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function trainingId(): TrainingId
    {
        return $this->trainingId;
    }
}
